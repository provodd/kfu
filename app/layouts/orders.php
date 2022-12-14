<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заказы</title>
    <style>
        .img-size-32 {
            width: 32px;
        }

        .img-size-64,
        .img-size-50,
        .img-size-32 {
            height: auto;
        }

        .mr-3,
        .mx-3 {
            margin-right: 1rem !important;
        }

        .pointer {
            cursor: pointer;
        }

        .card-body hr:first-child {
            display: none;
        }
    </style>
    <?php include 'components/header.php'; ?>
    <?php include 'components/navbar.php'; ?>

    <section class="section-sm" style="background-color: #f7f7f7;">
        <div id="preloader" class="visible"></div>
        <div class="container">
            <?php if (isset($_SESSION['logged_user'])) {
                ?>
                <div class="custom-panel-overflow p-1">
                    <?php
                    $j = 0;
                    $orders = R::getAll("SELECT * FROM cart_orders WHERE id_user={$_SESSION['logged_user']['id']} GROUP BY id_order ORDER BY id DESC");
                    if ($orders) {
                        foreach ($orders as $val) {
                            $j++;
                            ?>
                            <div class="card my-1 mt-2">
                                <div class="card-header pointer bg-gray-light" data-toggle="collapse"
                                     data-target="#collapse_<?= $j ?>"><b> Заказ #<?= $val['id_order'] ?>
                                        от <?= date('d.m.Y', strtotime($val['datetime'])) ?> </b>
                                </div>
                                <div class="collapse p-1 show" id="collapse_<?= $j ?>">
                                    <div class="card-body p-0">
                                        <?php
                                        $ord = R::getAll("SELECT * FROM cart_orders WHERE id_order='{$val['id_order']}'");
                                        foreach ($ord as $item) {
                                            $prod = R::findOne('ek_products', 'id=?', array($item['id_product']));
                                            $status = R::findOne('cart_orders_status', 'id=?', array($item['status']));

                                            if (isset($images) and is_file($_SERVER['DOCUMENT_ROOT'] . '/uploads/products/' . $prod->product_img . '')) {
                                                $prodImg = '/uploads/products/' . $prod->product_img;
                                            } else {
                                                $prodImg = '/app/assets/images/no-image3.png';
                                            }
                                            ?>
                                            <hr>
                                            <div class="row p-3">

                                                <div class="col-md-6">
                                                    <img width="70px" src="<?= $prodImg ?>"
                                                         alt="Product 1" class="img mr-3 img-thumbnail">
                                                    <a href="/single?id=<?= $prod->id ?>"><?= $item['product_name'] ?></a>
                                                </div>

                                                <div class="col-md-2">
                                                    <?= $item['kolvo'] ?> шт.
                                                </div class="col-md-2">

                                                <div class="col-md-2">
                                                    <div class="<?= $status->badge ?>"><?= $status->status_name ?></div>
                                                </div>

                                                <div class="col-md-2">3 500,00 ₽</div>
                                            </div>

                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        <?php }
                    } else {
                        echo '<h1 class="mb-3 mt-4">Список заказов пуст</h1>';
                        echo '<p class="mt-3">Воспользуйтесь <a href="/catalog">каталогом</a>, чтобы найти всё что нужно.</p>';
                    } ?>
                </div>
            <?php } else {
                echo '<p><a class="login-button">Авторизуйтесь</a> чтобы посмотреть список заказов.</p>';
            } ?>
        </div>
    </section>


    <?php include 'components/footer.php'; ?>

    <script>
        window.onload = function (url) {
            let preloaderEl = document.getElementById('preloader');
            preloaderEl.classList.add('hidden');
            preloaderEl.classList.remove('visible');
        }
    </script>
    </body>

</html>