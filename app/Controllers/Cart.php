<?php
namespace App\Controllers;
use provodd\base_framework\Db;
use App\Controllers\Helper;

class Cart extends Controller
{

    public function __construct()
    {
        parent::__construct();
        new Db();
    }
    public function index(){

        $params['title'] = 'Главная страница';

        parent::render('cart', $params);
    }
    public function handler(){
        if (isset($_POST)){
            $data = $_POST;
            //получение корзины
            if (isset($data['getCart'])) {
                $hash = $_COOKIE['user_hash'];
                if (isset($_SESSION['logged_user'])) {
                    $cart = \R::getAll("SELECT * FROM cart WHERE id_user = '{$_SESSION['logged_user']['id']}'");
                }else{
                    $cart = \R::getAll("SELECT * FROM cart WHERE user_hash = '$hash'");
                }
                $result['cart'] = $cart;
            }
            //добавление в корзину
            if (isset($data['addToCart'])) {
                //тут все поменять, если авторизован, то в приоритете
                $hash = $_COOKIE['user_hash'];
                $product = \R::findOne('ek_products', 'id=?', [$data['addToCart']]);

                $cart_hash = \R::findOne('cart', 'user_hash=? AND id_product=?', [
                    $hash,
                    $data['addToCart'],
                ]);
                if (isset($cart_hash)) {
                    $loadedCart = \R::load('cart', $cart_hash->id);
                    $loadedCart->kolvo = $cart_hash->kolvo + 1;
                    $id = \R::store($loadedCart);
                } else {
                    if (isset($_SESSION['logged_user']['id'])) {
                        $id_user = $_SESSION['logged_user']['id'];
                    } else {
                        $id_user = null;
                    }

                    $cart_id = \R::findOne('cart', 'id_user=? AND id_product=?', [
                        $id_user,
                        $data['addToCart'],
                    ]);
                    if (isset($cart_id)) {
                        $loadedCart = \R::load('cart', $cart_id->id);
                        $loadedCart->kolvo = $cart_id->kolvo + 1;
                        $id = \R::store($loadedCart);
                    } else {
                        $loadedCart = \R::dispense('cart');
                        $loadedCart->id_product = $product->id;
                        $loadedCart->id_category = $product->id_category;
                        $loadedCart->id_user = $id_user;
                        $loadedCart->product_name = $product->product_name;
                        $loadedCart->product_img = $product->product_img;
                        $loadedCart->descr = $product->descr;
                        $loadedCart->full_descr = $product->full_descr;
                        $loadedCart->kolvo = '1';
                        $loadedCart->datetime = date('Y-m-d H:i:s');
                        $loadedCart->price = $product->price;
                        $loadedCart->user_hash = $hash;
                        $id = \R::store($loadedCart);
                    }
                }
                if ($id) {
                    $products = \R::getAll('SELECT * FROM ek_products');
                    $cart = \R::getAll("SELECT * FROM cart WHERE user_hash='$hash'");

                    $result['cart'] = $cart;
                    $result['addedItem'] = $id;
                    $result['products'] = $products;
                }
            }
            //изменение количества в корзине
            if (isset($data['editCount'])) {
                if ($_SESSION['logged_user']) {
                    $c = \R::findOne('cart', 'id_product=? AND id_user=?', [
                        $data['editCount'],
                        $_SESSION['logged_user']['id'],
                    ]);
                    $cart_array = \R::getAll(
                        "SELECT * FROM cart WHERE id_user={$_SESSION['logged_user']['id']}"
                    );
                } else {
                    $hash = $_COOKIE['user_hash'];
                    $c = \R::findOne('cart', 'id_product=? AND user_hash=?', [
                        $data['editCount'],
                        $hash,
                    ]);
                    $cart_array = \R::getAll(
                        "SELECT * FROM cart WHERE user_hash='$hash'"
                    );
                }
                if ($c) {
                    $cart = \R::load('cart', $c->id);
                    $cart->kolvo = $data['count'];

                    if (\R::store($cart)) {
                        $result['sumcart'] = $data['count'] * $c->price;
                        $sum = 0;
                        foreach ($cart_array as $v) {
                            $sum = $sum + $v['price'] * $v['kolvo'];
                        }
                        $result['sumcartall'] = $sum;
                    } else {
                        $result['sumcart'] = $c->kolvo * $c->price;
                    }
                } else {
                    $result['status'] = 'error';
                }
            }
            //удаление из корзины
            if (isset($data['deleteItem'])) {
                function remove($id)
                {
                    $bean = \R::findOne('cart', 'id = ? ', [$id]);
                    if ($bean !== null) {
                        \R::trash($bean);
                        return true;
                    }
                    return false;
                }

                if (remove($data['deleteItem'])) {
                    $result['status'] = 'ok';
                    $result['text'] = 'Товар удален из корзины';
                } else {
                    $result['status'] = 'error';
                    $result['text'] = 'Не удалось удалить товар из корзины';
                }
            }
            if (isset($data['sendOrder'])) {

                $cart = \R::getAll("SELECT * FROM cart WHERE id_user={$_SESSION['logged_user']['id']}");
                $i = 0;
                $order_id = '';
                if (isset($cart)) {
                    $order_id = 'ORD' . Helper::instance()->uniqidReal() . '-' . $_SESSION['logged_user']['id'];
                    foreach ($cart as $item) {
                        $p = \R::findOne('ek_products', 'id=?', [$item['id_product']]);

                        $add = \R::xdispense('cart_orders');
                        $add->id_order = $order_id;
                        $add->id_product = $item['id_product'];
                        $add->id_category = $item['id_category'];
                        $add->id_user = $_SESSION['logged_user']['id'];
                        $add->product_name = $item['product_name'];
                        $add->descr = $item['descr'];
                        $add->full_descr = $item['full_descr'];
                        $add->price = $item['price'];
                        $add->kolvo = $item['kolvo'];
                        $add->id_org = $_SESSION['logged_user']['id_org'];
                        $add->datetime = date('Y-m-d H:i:s');
                        $add->status = 1;
                        if (\R::store($add)) {
                            $i++;
                        }
                        $cat = \R::load('cart', $item['id']);
                        \R::trash($cat);
                    }
                }

                if ($i > 0) {
                        $result['status'] = 'ok';
                        $result['text'] = '
                            <div class="jumbotron w-100 text-center">
                             <h4 class="display-4">Заказ успешно оформлен</h4>
                             <hr>
                             <p>
                                 Возникли проблемы? <a href="/contact">Свяжитесь с нами</a>
                             </p>
                             <p class="lead">
                                 <a class="btn btn-primary btn-sm" href="/orders" role="button">Перейти к заказам</a>
                             </p>
                         </div>';
                    } else {
                        $result['status'] = 'error';
                    }
            }
        }
        echo isset($result) ? json_encode($result) : false;
    }
}
