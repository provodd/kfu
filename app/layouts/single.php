<?php

$id = $_GET['id'];
$prod = R::findOne('ek_products', 'id=?', array($id));
$cat = R::findOne('ek_categories', 'id=?', array($prod->id_category));
$rew = R::getAll("SELECT AVG(stars) AS avgstars FROM review WHERE id_product='$id'");
if (isset($rew)) {
    $score  = $rew[0]['avgstars'];
} else {
    $score = NULL;
}
if ($prod->nalichie>0){
    $availability = '<a class="text-success">В наличии '.$prod->nalichie.' шт.</a>';
}
else if ($prod->kolvo>0){
    $availability = '<a class="text-success">В наличии</a>';
}else{
    $availability = '<a class="text-primary">Под заказ</a>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- SITE TITTLE -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $prod->product_name ?> - купить с доставкой по России и СНГ">
    <title><?= $prod->product_name ?></title>

    <?php include 'components/header.php'; ?>
    <?php include 'components/navbar.php'; ?>


    <section class="section bg-gray">
        <div id="preloader" class="visible"></div>

        <div class="container">
            <div class="row">
                <!-- Left sidebar -->
                <div class="col-md-8">
                    <div class="product-details">
                        <h1 class="product-title"><?= $prod->product_name ?></h1>
                        <div class="product-meta">
                            <ul class="list-inline">
                                <li class="list-inline-item"><i class="fa fa-folder-open-o"></i> Категория<a href="catalog?cat=<?= $cat->id ?>"><?= $cat->category_name ?></a></li>
                            </ul>
                        </div>

                        <!-- product slider -->

                        <div class="product-slider">
                            <?php
                            $images = explode(",", $prod->product_img);
                            if (isset($images[0]) AND is_file($_SERVER['DOCUMENT_ROOT'].'/uploads/products/'.$images[0].'')) {

                                foreach ($images as $val) { ?>
                                    <div class="product-slider-item my-4" data-image="/uploads/products/<?= $val ?>">
                                        <img class="img-fluid img-fluid img-thumbnail w-75" src="/uploads/products/<?= $val ?>" alt="product-img">
                                    </div>

                                <?php }
                            } else {
                                echo '<div class="product-slider-item my-4" data-image="/app/assets/images/no-image3.png">
                                        <img class="img-fluid img-fluid img-thumbnail w-75"
                                             src="/app/assets/images/no-image3.png" alt="product-img">
                                    </div>';
                            } ?>
                        </div>
                        <!-- product slider -->

                        <div class="content mt-6 pt-6">
                            <ul class="nav nav-pills  justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Описание</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Характеристики</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Отзывы</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <h3 class="tab-title">Описание товара</h3>
                                    <p><?= $prod->descr ?></p>
                                    <p></p>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <h3 class="tab-title">Характеристики</h3>
                                    <table class="table table-bordered product-table">
                                        <tbody>
                                        <tr>
                                            <td>Цена</td>
                                            <td><?= number_format($prod->price, 2, ',', ' '); ?>&nbsp;<span>₽</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Категория</td>
                                            <td><?= $cat->category_name ?></td>
                                        </tr>
                                        <tr>
                                            <td>Вес</td>
                                            <td><?= $prod->weight ?> кг.</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <h3 class="tab-title">Отзывы</h3>
                                    <div class="product-review">
                                        <div id="product-review">

                                            <?php
                                            $reviews = R::getAll("SELECT * FROM review WHERE id_product = {$_GET['id']}");
                                            if ($reviews) {
                                                foreach ($reviews as $review) {
                                                    $user = R::findOne('users', 'id=?', array($review['id_user']));
                                                    ?>
                                                    <div class="media">
                                                        <!-- Avatar -->
                                                        <?php
                                                        if (isset($user->avatar)) {
                                                            if (file_exists("/app/assets/images/avatar/' . $user->avatar . '")){
                                                                echo '<img src="/app/assets/images/avatar/' . $user->avatar . '" alt="avatar">';
                                                            }else{
                                                                echo '<img src="/app/assets/images/avatar/unnamed.jpg" alt="avatar">';
                                                            }

                                                        } else {
                                                            echo '<img src="/app/assets/images/avatar/unnamed.jpg" alt="avatar">';
                                                        };
                                                        ?>

                                                        <div class="media-body">
                                                            <!-- Ratings -->
                                                            <div class="ratings">
                                                                <ul class="list-inline">
                                                                    <?php
                                                                    $a = 5;
                                                                    $b = intval($review['stars']);
                                                                    $c = $a - $b;
                                                                    if ($b) {
                                                                        for ($i = 1; $i <= $b; $i++) {
                                                                            echo '<li class="list-inline-item">
                                                                            <i class="fa fa-star"></i>
                                                                        </li>';
                                                                        }
                                                                    }
                                                                    if ($c) {
                                                                        for ($j = 1; $j <= $c; $j++) {
                                                                            echo '<li class="list-inline-item">
                                                                            <i class="fa fa-star-o"></i>
                                                                        </li>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                            <div class="name">
                                                                <h5><?= $user->fio ?></h5>
                                                            </div>
                                                            <div class="date">
                                                                <p><?= $review['datetime'] ?></p>
                                                            </div>
                                                            <div class="review-comment">
                                                                <p>
                                                                    <?= $review['text'] ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } else {
                                                ?>
                                                <p>Помогите другим пользователям с выбором — будьте первым, кто
                                                    поделится своим мнением об этом товаре.</p>

                                                <?php
                                            } ?>


                                        </div>
                                        <div class="review-submission">
                                            <h3 class="tab-title">Оставить свой отзыв</h3>
                                            <?php
                                            if (isset($_SESSION['logged_user'])) {

                                                ?>
                                                <div class="rate">
                                                    <div class="starrr review-stars"></div>
                                                </div>
                                                <div class="review-submit">
                                                    <form action="#" method="POST" class="row" name="form-review" id="form-review">
                                                        <div class="col-12">
                                                            <textarea required name="text-review" id="review" rows="10" class="form-control" placeholder="Сообщение"></textarea>
                                                        </div>
                                                        <div class="col-12">
                                                            <div idp="<?= $_GET['id'] ?>" class="btn btn-main send-review">Отправить
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <p><a class="login-button pointer">Войдите</a> или зарегистрируйтесь,
                                                    что бы оставить отзыв</p>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sidebar">
                        <div class="widget user text-center">
                            <div class="row">
                                <div class="col-md-11" style="font-size: 2em; font-weight:600;">
                                    <?= number_format($prod->price, 2, ',', ' '); ?>&nbsp;<span>₽</span>
                                </div>
                                <div class="col-md-1">
                                </div>
                            </div>

                            <h4 class="mt-3"><?=$availability?></h4>
                            <p class="member-time"></p>
                            <a>Картой онлайн, наличными</a>
                            <ul class="list-inline mt-20">
                                <li class="list-inline-item w-100" id="btn-to-cart">
                                    <?php
                                    if (isset($_SESSION['logged_user'])) {
                                        $productInCart = R::findOne('cart', 'id_product=? AND id_user=?', array($prod->id, $_SESSION['logged_user']['id']));
                                    } else {
                                        $productInCart = R::findOne('cart', 'id_product=? AND user_hash=?', array($prod->id, $_COOKIE['user_hash']));
                                    }

                                    if ($productInCart) {
                                        ?>
                                        <a href="cart" class="btn btn-success text-light btn-block">Оформить
                                            заказ</a>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="btn btn-primary btn-block add-to-cart" idp="<?= $prod->id ?>">
                                            Добавить в корзину
                                        </div>
                                        <?php
                                    }
                                    ?>

                                </li>
                            </ul>
                        </div>
                        <!-- Rate Widget -->
                        <div class="widget rate text-center">
                            <!-- Heading -->
                            <h5 class="widget-header text-center">Средняя оценка
                                <br>
                                данному товару
                            </h5>
                            <!-- Rate -->
                            <div id="single-stars"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- Container End -->
    </section>

    <?php include 'components/footer.php'; ?>
    <script>
        $(document).ready(function() {

            let stars = document.querySelector('#single-stars');
            $('#single-stars').raty({
                readOnly: true,
                <?php if (isset($score)) {
                    echo 'score:' . $score . ',';
                } ?>
            });
        })
    </script>
    <script type="module" defer>
        import addParameter from "/app/assets/js/functions/addParameter.js";
        import setLocation from "/app/assets/js/functions/setLocation.js";
        import setPreloader from "/app/assets/js/functions/setPreloader.js";
        import Catalog from "/app/assets/js/classes/Catalog.js";
        import Cart from "/app/assets/js/classes/Cart.js";


        let dataSource = {};
        let catalog = new Catalog(dataSource);
        window.catalog = catalog;
        //0-none ajax,  1-with ajax none view, 2-with ajax,

        catalog.loadCatalog('1');

        let inProgress = false;

        //catalog.loadData('cat=<?= $prod->id_category ?>', '1');

        document.addEventListener("DOMContentLoaded", function(event) {


            document.onclick = function(event) {

                let target = event.target;

                if (target.classList.contains("add-to-cart")) {

                    setPreloader('show');

                    let idp = target.getAttribute('idp');
                    let cart = new Cart(catalog.dataSource);

                    cart.addToCart(idp, 'false');
                    cart.getCartModal(idp);

                } else if (target.classList.contains("btn-plus") || target.classList.contains("btn-minus")) {
                    setPreloader('show');
                    let id_product = target.getAttribute('idp');
                    let inputValue = Number(document.getElementById('cart-item-count' + id_product).value);

                    if (target.classList.contains("btn-plus")) {
                        document.getElementById('cart-item-count' + id_product).value = inputValue + 1;
                    } else if (target.classList.contains("btn-minus")) {
                        document.getElementById('cart-item-count' + id_product).value = inputValue - 1;
                    }

                    let val = Number(document.getElementById('cart-item-count' + id_product).value);


                    let cart = new Cart(catalog.dataSource);
                    cart.editCount(id_product, val);

                }
            }
        });
    </script>
    </body>

</html>