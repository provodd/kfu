<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .card-rus {
            margin: auto;
            max-width: 960px;
            width: 90%;
            box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            border-radius: 1rem;
            border: transparent;
            /* padding-left: 30px; */
        }

        @media (max-width: 767px) {
            .card-rus {
                margin: 3vh auto
            }
        }

        .cart {
            background-color: #fff;
            padding: 4vh 5vh;
            border-bottom-left-radius: 1rem;
            border-top-left-radius: 1rem
        }

        @media (max-width: 767px) {
            .cart {
                padding: 4vh;
                border-bottom-left-radius: unset;
                border-top-right-radius: 1rem
            }
        }

        .summary {
            background-color: #ddd;
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
            padding: 4vh;
            color: rgb(65, 65, 65)
        }

        @media (max-width: 767px) {
            .summary {
                border-top-right-radius: unset;
                border-bottom-left-radius: 1rem
            }
        }

        .summary .col-2 {
            padding: 0
        }

        .summary .col-10 {
            padding: 0
        }

        .row {
            margin: 0
        }

        .title b {
            font-size: 1.5rem
        }

        .main {
            margin: 0;
            padding: 2vh 0;
            width: 100%
        }

        .col-2,
        .col {
            padding: 0 1vh
        }

        .a {
            padding: 0 1vh
        }

        .close {
            margin-left: auto;
            font-size: 0.7rem
        }

        .img-rus {
            width: 3.5rem
        }

        .back-to-shop {
            margin-top: 4.5rem
        }

        h5 {
            margin-top: 4vh
        }

        hr {
            margin-top: 1.25rem
        }

        form {
            padding: 2vh 0
        }

        select {
            border: 1px solid rgba(0, 0, 0, 0.137);
            padding: 1.5vh 1vh;
            margin-bottom: 4vh;
            outline: none;
            width: 100%;
            background-color: rgb(247, 247, 247)
        }

        input {
            border: 1px solid rgba(0, 0, 0, 0.137);
            padding: 1vh;
            margin-bottom: 4vh;
            outline: none;
            width: 100%;
            background-color: rgb(247, 247, 247)
        }

        input:focus::-webkit-input-placeholder {
            color: transparent
        }

        .btn {
            background-color: #000;
            border-color: #000;
            color: white;
            width: 100%;
            font-size: 0.7rem;
            margin-top: 4vh;
            padding: 1vh;
            border-radius: 0
        }

        .btn:focus {
            box-shadow: none;
            outline: none;
            box-shadow: none;
            color: white;
            -webkit-box-shadow: none;
            -webkit-user-select: none;
            transition: none
        }

        .btn:hover {
            color: white
        }

        a {
            color: black
        }

        a:hover {
            color: black;
            text-decoration: none
        }

        #code {
            background-image: linear-gradient(to left, rgba(255, 255, 255, 0.253), rgba(255, 255, 255, 0.185)), url("https://img.icons8.com/small/16/000000/long-arrow-right.png");
            background-repeat: no-repeat;
            background-position-x: 95%;
            background-position-y: center
        }
    </style>
    <!-- SITE TITTLE -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Корзина</title>

    <?php include 'components/header.php'; ?>
    <?php include 'components/navbar.php'; ?>
    <div id="preloader" class="visible"></div>
    <?php

    $hash = $_COOKIE['user_hash'];
    if (isset($_SESSION['logged_user'])) {
        $query = " OR id_user = '{$_SESSION['logged_user']['id']}'";
    } else {
        $query = '';
    }
    $cart = R::getAll("SELECT * FROM cart WHERE user_hash = '$hash' $query");
    if ($cart) {
        ?>
        <section class="section-sm" style="background-color: #f7f7f7;">

            <div class="container" id="result-cart">
                <div class="card card-rus">
                    <div class="row">
                        <div class="col-md-8 cart">
                            <div class="title">
                                <div class="row border-bottom">
                                    <div class="col">
                                        <h4><b>Корзина</b></h4>
                                    </div>
                                    <div class="col align-self-center text-right text-muted"></div>
                                </div>
                            </div>
                            <div id="cart-items">

                            </div>
                            <div class="back-to-shop"><a href="catalog">&leftarrow;</a><span class="text-muted"><a href="catalog"> Вернуться в каталог</a></span></div>
                        </div>
                        <div class="col-md-4 summary">
                            <div>
                                <h5><b>Итого</b></h5>
                            </div>
                            <div class="row mt-2">
                                <div class="col" style="padding-left:0;"></div>
                                <div class="col text-right"></div>
                            </div>
                            <form>
                                <p class="mb-0 mt-2">Доставка</p> <select>
                                    <option class="text-muted">Самовывоз - &#8381;0</option>
                                </select>
                                <p class="mb-0 mt-2">Промо код</p> <input id="code" disabled placeholder="Нет доступных промокодов">
                            </form>
                            <div class="row mt-3">
                                <div class="col">Стоимость</div>
                                <div class="col text-right" id="priceCart">&#8381;</div>
                            </div>
                            <?php
                            if (isset($_SESSION['logged_user'])) {
                                $user = R::findOne('users','id=?', array($_SESSION['logged_user']['id']));
                                echo '<button class="btn btn-primary send-order-fiz">Оформить</button>';
                            } else {
                                echo "<div class='mt-3'> <a class='login-button btn-primary text-light m-0 p-0'>Войдите</a>
                                         или <a class='pointer text-primary reg-button'>зарегистрируйтесь</a>
                                            чтобы делать покупки, отслеживать заказы и пользоваться персональными скидками
                                    </div>";
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } else {
        echo '<div class="container mt-4 pb-3 pt-3" style="min-height:500px;">
            <h1 class="mb-3 mt-4">Корзина пуста</h1>
            <p class="mt-3">Воспользуйтесь <a href="catalog">каталогом</a>, чтобы найти всё что нужно.</p>';

        if (!isset($_SESSION['logged_user'])) {
            echo '<p>Если в корзине были товары – <a class="login-button">войдите</a>, чтобы посмотреть список.</p>';
        }
        echo '</div>';
    } ?>

    <?php include 'components/footer.php'; ?>
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

        let cart = new Cart(catalog.dataSource);
        cart.getCart();

        document.addEventListener("DOMContentLoaded", function(event) {

            document.onclick = function(event) {

                let target = event.target;
                if (target.classList.contains("send-order-fiz")) {
                    cart.sendOrder('fiz');
                } else if (target.classList.contains("send-order")) {
                    cart.sendOrder('yur');
                } else if (target.classList.contains("btn-plus") || target.classList.contains("btn-minus")) {
                    catalog.getCartCount();
                    let id_product = target.getAttribute('idp');

                    let inputValue = Number(document.getElementById('cart-item-count' + id_product).value);
                    let inputCount = document.getElementById('cart-item-count' + id_product);

                    if (target.classList.contains("btn-plus")) {
                        inputCount.value = inputValue + 1;
                    } else if (target.classList.contains("btn-minus")) {
                        inputCount.value = inputValue - 1;
                    }

                    let val = Number(document.getElementById('cart-item-count' + id_product).value);
                    cart.editCount(id_product, val, true);
                } else if (target.classList.contains('delete-cart-item')) {
                    catalog.getCartCount();
                    let id_cart = target.getAttribute('idc');
                    //let id_product = target.getAttribute('idp');

                    cart.deleteCartItem(id_cart);

                }
            }
        });
    </script>
    </body>

</html>