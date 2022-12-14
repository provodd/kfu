<?php

?>

<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Каталог запчастей">
    <title>Каталог запчастей</title>

    <style>
        .sectionOverflow {
            max-height: 550px;
            padding: 1rem;
            overflow-y: auto;
            direction: ltr;
            scrollbar-color: #d4aa70 #e4e4e4;
            scrollbar-width: thin;
            overflow-x: hidden;

        h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        p+p {
            margin-top: 1rem;
        }

        }

        .sectionOverflow::-webkit-scrollbar {
            width: 10px;
        }

        .sectionOverflow::-webkit-scrollbar-track {
            background-color: #e4e4e4;
            border-radius: 100px;
        }

        .sectionOverflow::-webkit-scrollbar-thumb {
            border-radius: 100px;
            background-image: linear-gradient(180deg, #d0368a 0%, #708ad4 99%);
            box-shadow: inset 2px 2px 5px 0 rgba(#fff, 0.5);
        }
    </style>
    <?php include 'components/header.php'; ?>
    <?php include 'components/navbar.php'; ?>

    <section class="section-sm" style="min-height:600px;">
        <div id="preloader" class="visible"></div>

        <div class="container container-catalog" style="display:none;">
            <div class="row">
                <div class="form-control-group col-md-12 mb-3">
                    <a class="text-muted">
                        <i class="fa-rus fa fa-search" aria-hidden="true"></i>
                    </a>
                    <input type="text" id="elastic" placeholder="Поиск по артиклу, наименованию или категории">

                </div>
                <div class="row w-100 pl-3 mb-3">
                    <div class="col-md-6">
                        <ul class="elastic elastic-categories">
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="elastic elastic-products">
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div id="catalog-result" class="search-result bg-gray">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="category-sidebar">
                        <div class="widget p-0 pr-1 sectionOverflow category-list">
                            <h4 class="widget-header"><a class="category_link" href="">Все категории</a></h4>

                            <ul class="category-list category-list-catalog">


                            </ul>
                        </div>

                        <div class="widget price-range w-100">
                            <h4 class="widget-header">Цена</h4>
                            <div class="block block-slider-price">
                                <!-- data-slider-min="0" data-slider-max="5000" data-slider-step="5" data-slider-value="[0,5000] -->

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-9">

                    <div class="category-search-filter">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Сортировка</strong>
                                <select class="catalog-sort">
                                    <?php
                                    if ($_GET['sort']) {
                                    }

                                    $array_sort_types = array(
                                        0 => array(
                                            'id' => '0', 'option' => '<option value="0">По релевантности</option>', 'option_selected' => '<option selected value="0">Самые дорогие</option>',
                                        ),
                                        1 => array(
                                            'id' => '1', 'option' => '<option value="1">Самые популярные</option>', 'option_selected' => '<option selected value="1">Самые популярные</option>',
                                        ),
                                        2 => array(
                                            'id' => '2', 'option' => '<option value="2">Самые дешевые</option>', 'option_selected' => '<option selected value="2">Самые дешевые</option>',
                                        ),
                                        4 => array(
                                            'id' => '4', 'option' => '<option value="4">Самые дорогие</option>', 'option_selected' => '<option selected value="4">Самые дорогие</option>',
                                        )
                                    );
                                    foreach ($array_sort_types as $sort_type) {
                                        if ($sort_type['id'] == $_GET['sort']) {
                                            echo $sort_type['option_selected'];
                                        } else {
                                            echo $sort_type['option'];
                                        }
                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="view d-none">
                                    <strong>Вид</strong>
                                    <ul class="list-inline view-switcher">
                                        <li class="list-inline-item">
                                            <a href="#" onclick="event.preventDefault();" class="text-info"><i class="fa fa-th-large"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href=""><i class="fa fa-reorder"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3" id="product-grid-list">
                        <div id="preloader-catalog" class="visible"></div>
                    </div>


                </div>
            </div>
        </div>
    </section>


    <?php include 'components/footer.php'; ?>
    <script type="module" defer src="/app/assets/js/catalog.js"></script>
    </body>

</html>