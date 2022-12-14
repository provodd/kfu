<?php

namespace App\Controllers;
use provodd\base_framework\Db;

class Catalog extends Controller
{
    public function __construct()
    {
        parent::__construct();
        new Db();
    }

    public function index()
    {

        $params['title'] = 'Каталог';


        $products = \R::getAll('SELECT * FROM ek_products WHERE visible!="0" LIMIT 5');
        $categories = \R::getAll('SELECT id_category, COUNT(*) as Qty, cats.category_name FROM ek_products 
LEFT JOIN ek_categories as cats ON cats.id = ek_products.id_category  WHERE visible!="0" GROUP BY id_category ORDER BY Qty DESC');


        $params['products'] = $products;
        $params['categories'] = $categories;

        parent::render('catalog', $params);

    }

    public function handler()
    {
        if (isset($_POST)) {
            $data = $_POST;

            if (isset($data['getProducts'])) {
                $products = \R::getAll('SELECT ek_products.*, cat.category_name FROM ek_products 
            LEFT JOIN ek_categories AS cat ON cat.id = ek_products.id_category WHERE visible!="0"');

                $arr = array();
                foreach ($products as $key=> $prod){
                    $arr[$key] = $prod;

                    $rew = \R::getAll("SELECT AVG(stars) AS avgstars FROM review WHERE id_product={$prod['id']}");

                    if (isset($rew)){
                        $arr[$key]['avgstars'] = $rew[0]['avgstars'];
                    }
                }

                $cats = \R::getAll("SELECT DISTINCT id_category FROM ek_products WHERE visible!='0'");
                foreach ($cats as $cat) {
                    $category = \R::findOne('ek_categories', 'id=?', [
                        $cat['id_category'],
                    ]);
                    $count_cat = \R::count('ek_products', 'id_category=?', [
                        $category['id'],
                    ]);

                    $rew = \R::findOne('ek_categories', 'id=?', [
                        $cat['id_category'],
                    ]);
                    $array['id'] = $category->id;
                    $array['category_name'] = $category->category_name;
                    $array['count'] = $count_cat;

                    $array_cats[] = $array;
                }


                if (isset($_SESSION['logged_user']['id'])) {
                    $uid = $_SESSION['logged_user']['id'];
                    $cart = \R::getAll("SELECT * FROM cart WHERE id_user='$uid'");
                } else {
                    $hash = $_COOKIE['user_hash'];
                    $cart = \R::getAll("SELECT * FROM cart WHERE user_hash='$hash'");
                }

                $result['cart'] = $cart;
                $result['products'] = $arr;
                $result['categories'] = $array_cats;

            }
        }
        echo isset($result) ? json_encode($result) : false;
    }
}
