<?php

?>
<!DOCTYPE html>
<html lang="ru">

<head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Запчасти для грузовых автомобилей с доставкой по России и СНГ">
    <title>Главная страница</title>

    <?php include 'components/header.php'; ?>
    <?php include 'components/navbar.php'; ?>
    <?php include 'components/hero-area.php'; ?>

    <div id="preloader" class="visible"></div>

    <section class="section bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>Популярные товары</h2>
                        <p>Все новинки. Специально для вас.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- offer 01 -->
                <div class="col-lg-12">
                    <div class="trending-ads-slide">

                        <?php
                        foreach ($params['products'] as $item) {
                            $category = R::findOne('ek_categories', 'id=?', array($item['id_category']));
                            $images = $item['product_img'] ? explode(",", $item['product_img']) : NULL;
                            ?>
                            <div class="col-sm-12 col-lg-4">
                                <!-- product card -->
                                <div class="product-item bg-light">
                                    <div class="card">
                                        <div class="thumb-content">
                                            <!-- <div class="price">$200</div> -->
                                            <a href="single?id=<?=$item['id']?>">
                                                <?php

                                                if (isset($images) AND is_file($_SERVER['DOCUMENT_ROOT'].'/uploads/products/'.$images[0].'')) {
                                                    ?>
                                                    <img class="card-img-top img-fluid p-3" height="100px" src="/uploads/products/<?= $images[0] ?>">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <img class="card-img-top img-fluid p-3" height="100px" src="app/assets/images/no-image3.png">
                                                    <?php
                                                } ?>
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title"><a href="single?id=<?=$item['id']?>"><?= $item['product_name'] ?></a></h4>
                                            <ul class="list-inline product-meta">
                                                <li class="list-inline-item">
                                                    <a href="catalog?cat=<?= $category->id ?>"><i class="fa fa-folder-open-o"></i><?= $category->category_name ?></a>
                                                </li>
                                            </ul>
                                            <p class="card-text"><?= $item['descr'] ?></p>
                                            <div class="product-ratings">
                                                <ul class="list-inline">
                                                    <li class="list-inline-item selected"><i class="fa fa-star"></i></li>
                                                    <li class="list-inline-item selected"><i class="fa fa-star"></i></li>
                                                    <li class="list-inline-item selected"><i class="fa fa-star"></i></li>
                                                    <li class="list-inline-item selected"><i class="fa fa-star"></i></li>
                                                    <li class="list-inline-item"><i class="fa fa-star"></i></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Популярные категории</h2>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 offset-lg-0 col-md-5 offset-md-1 col-sm-6 col-6">
                            <div class="category-block">
                                <ul class="category-list">
                                    <?php
                                    foreach (array_slice($params['categories'], 0, 10) as $c1){
                                        echo '<li><a href="catalog?cat='.$c1['id_category'].'">'.$c1['category_name'].' <span>'.$c1['Qty'].'</span></a></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 offset-lg-0 col-md-5 offset-md-1 col-sm-6 col-6">
                            <div class="category-block">
                                <ul class="category-list">
                                    <?php
                                    foreach (array_slice($params['categories'], 10, 10) as $c2){
                                        echo '<li><a href="catalog?cat='.$c2['id_category'].'">'.$c2['category_name'].' <span>'.$c2['Qty'].'</span></a></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 offset-lg-0 col-md-5 offset-md-1 col-sm-6 col-6">
                            <div class="category-block">
                                <ul class="category-list">
                                    <?php
                                    foreach (array_slice($params['categories'], 20, 10) as $c3){
                                        echo '<li><a href="catalog?cat='.$c3['id_category'].'">'.$c3['category_name'].' <span>'.$c3['Qty'].'</span></a></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php include 'components/footer.php'; ?>
    <script type="module" defer>
        import setPreloader from "/app/assets/js/functions/setPreloader.js";
        setPreloader('hide');
    </script>

    </body>

</html>