import explode from "/app/assets/js/functions/explode.js?ver=2";
import setPrice from "/app/assets/js/functions/setPrice.js?ver=2";

export default class Catalog {

    startFrom = 0;
    dataView = [];
    dataProducts;
    cartCount = 0;

    constructor(dataSource) {
        this.dataSource = dataSource;
    }

    getData() {
        return this.dataSource;
    }

    getCartCount() {
        if (this.dataSource.cart) {
            let count = Object.keys(this.dataSource.cart).length;
            let span = document.querySelector('.cart-count-span');
            if (span) {
                if (count > 0) {
                    span.innerHTML = '<div class="badge badge-secondary">' + count + '</div>';
                } else {
                    span.innerHTML = '';
                }

            }
            return span;
        }
    }

    getExtr(data, extr) {

        let extremum;
        if (extr == 'max') {
            extremum = Math.max.apply(Math, data.map(o => o.price));
            this.maxPrice = extr;
        } else if (extr == 'min') {
            extremum = Math.min.apply(Math, data.map(o => o.price));
            this.minPrice = extr;
        }
        return extremum;
    }

    getSliderPrice(minPrice, maxPrice) {

        let elem = `
        <input data-slider-min="` + minPrice + `" data-slider-max="` + maxPrice + `" data-slider-step="5" data-slider-value="[` + minPrice + `,` + maxPrice + `]" class="range-track-price w-100" type="text">
        <div class="d-flex justify-content-between mt-2">
            <span class="value">₽ ` + minPrice + ` - ₽ ` + maxPrice + `</span>
        </div>
        `;
        return elem;
    }

    getCategories(categories) {
        let catList = '';
        categories.forEach(e => {
            catList = catList + `
            <li class="row pl-3 pr-3">
                <a class="col-10 category_link" href="cat=` + e.id + `">` + e.category_name + `</a>
                <a class="col-2"><span style="float: left;">` + e.count + `</span></a>
            </li>`;
        })
        return catList;
    }

    getProductsView(dataProducts, dataCart, p) {

        let productsHtmlView = '', elemLink, styleLink, styleIcon, inCart, productImage, images;
        //обрезаем массив с товарами, в дальнейшем при скролле страницы увеличиваем счетчик и уже добавляем последущие элементы из массива
        //поидее при применении фильтров надо отфильтровать текущий dataview 
        let x = dataProducts.slice(this.startFrom, this.startFrom + 10);
        let productCount = dataProducts.length;
        if (x.length > 0) {

            x.forEach(element => {
                this.dataView.push(element);
            })

            this.dataView.forEach(element => {
                let stars;
                let kolvo;
                if (element.kolvo > 0) {
                    kolvo = 'Есть в наличии';
                } else {
                    kolvo = 'Под заказ';
                }
                inCart = dataCart.find(x => x.id_product == element.id);
                if (inCart) {
                    styleLink = 'bg-success text-light';
                    styleIcon = 'fa-shopping-cart text-light';
                    elemLink = 'cart';
                } else {
                    styleLink = 'add-to-cart bg-primary text-light';
                    styleIcon = 'fa-cart-plus add-to-cart text-light';
                    elemLink = '';
                }
                if (!element.product_img) {
                    images = explode(',', element.product_img);

                    productImage = "/uploads/products/" + images[0];
                } else {
                    productImage = '/app/assets/images/no-image3.png';
                }

                if (element.avgstars) {
                    stars = '<p class="text-center">Средняя оценка: <i class="fa fa-star" aria-hidden="true"></i> ' + Number(element.avgstars).toFixed(1) + '</p>';
                } else {
                    stars = '<p class="text-center">Пока нет отзывов и оценок</p>';
                }
                productsHtmlView = productsHtmlView + `
            <div class="col-sm-6 col-md-4">
                    <div class="shop__thumb"> 
                        <a href="single?id=` + element.id + `">
                            <div class="shop-thumb__img">
                            ` + stars + `
                            <div class="thumb-content">
                            <div class="product-ratings">
                            <ul class="list-stars list-inline">

                            </ul>
                        </div>
                            <a href="single?id=` + element.id + `">
                                <img class="card-img-top img-fluid" src="` + productImage + `" alt="Изображение товара">
                            </a>
                        </div></div>

                            <h5 class="shop-thumb__title text-left"> <a href="single?id=` + element.id + `">` + (element.product_name) + `</a></h5>

                            <div class="shop-thumb__price d-none">
                                <span class="shop-thumb-price_old">&#8381; ` + (element.price * 1.2).toFixed(2) + `</span>
                                <span class="shop-thumb-price_new">&#8381; ` + element.price + `</span>
                            </div>
                            <div class="row">
                                <div class="col-8 text-left">` + setPrice(element.price) + ` <span class="text-muted">&#8381;</span></div>
                                <div class="col-4">
                                    <a href="` + elemLink + `" class="circle-rus ` + styleLink + `" idp="` + element.id + `" data-toggle="tooltip" data-placement="top" title="В корзину">
                                        <i class="fa ` + styleIcon + `" idp="` + element.id + `"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row"><div class="col-12 text-left text-muted" style="font-size:.9rem;">` + kolvo + `</div></div>
                        </a>
                    </div>
                </div>`;
            });
            let breadcrumbsView;

            if (p) {

                let urlParams = this.getUrlParams(p);

                if (urlParams['cat']) {
                    breadcrumbsView = `<h2>` + this.dataView[0].category_name + `</h2><p>` + productCount + ` результатов в данной категории</p>`;
                } else {
                    breadcrumbsView = `<h2>Все категории</h2>`;
                }

            } else {
                breadcrumbsView = `<h2>Все категории</h2><p>` + productCount + ` результатов</p>`;
            }
            let breadcrumbs = document.getElementById("catalog-result");
            breadcrumbs.innerHTML = breadcrumbsView;
            document.getElementById("product-grid-list").innerHTML = productsHtmlView;
            this.startFrom = Number(this.startFrom) + Number(10);
        }
    }

    getUrlParams(p) {

        return window.location.search.replace('?', '').split('&')
            .reduce(
                function (p, e) {
                    var a = e.split('=');
                    p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                    return p;
                }, {}
            );
    }

    setPreloader(type) {

        let preloaderEl = document.getElementById('preloader');
        if (type == 'hide') {
            preloaderEl.classList.add('hidden');
            preloaderEl.classList.remove('visible');
        } else {
            preloaderEl.classList.add('visible');
            preloaderEl.classList.remove('hidden');
        }

    }

    async loadCatalog(loadingType, params) {
        let strGET = window.location.search.replace('?', '');
        if (params == undefined || params == '') {
            //пока не актуальный функционал
        }
        strGET ? this.loadData(strGET, loadingType) : this.loadData('', loadingType);
    }

    //функция загрузки данных
    loadData(p, loadingType) {
        let dataProducts, dataCart, urlParams;

        if (loadingType == '0' || loadingType == '3' || loadingType == '4') {

            dataCart = this.dataSource.cart;
            dataProducts = this.dataSource.products;

            if (p) {

                urlParams = this.getUrlParams(p);

                if (urlParams['cat']) {
                    //здесь если изначально открть окно с середины, то будет ошибка, надо исправить
                    dataProducts = dataProducts.filter(data => data.id_category == urlParams['cat']);
                }
                if (urlParams['sort']) {

                    if (urlParams['sort'] == '2') {
                        dataProducts = dataProducts.sort(function (a, b) {
                            return a.price - b.price;
                        });
                    } else if (urlParams['sort'] == '1') {
                        dataProducts = dataProducts.sort(function (a, b) {
                            return b.avgstars - a.avgstars;
                        });
                    } else if (urlParams['sort'] == '0') {
                        dataProducts = dataProducts.sort(function (a, b) {
                            return b.weight - a.weight;
                        });
                    } else if (urlParams['sort'] == '4') {
                        dataProducts = dataProducts.sort(function (a, b) {
                            return b.price - a.price;
                        });
                    }
                }

                if (loadingType == '4') {
                    let maxPrice = this.getExtr(dataProducts, 'max');
                    let minPrice = this.getExtr(dataProducts, 'min');

                    let elBlockSliderPrice = document.querySelector('.block-slider-price');
                    elBlockSliderPrice.innerHTML = this.getSliderPrice(minPrice, maxPrice);

                    new Slider("input.range-track-price", {});
                }
                if (urlParams['price']) {
                    let urlPrice = urlParams['price'].split(';');
                    let minPrice = Number(urlPrice[0]);
                    let maxPrice = Number(urlPrice[1]);

                    dataProducts = dataProducts.filter((data) => {
                        return Number(Math.round(data.price)) >= minPrice && Number(Math.round(data.price)) <= maxPrice;
                    });
                }

                this.dataProducts = dataProducts;
            }

            this.getProductsView(dataProducts, dataCart, p);
            this.setPreloader('hide');

        } else if (loadingType == '1' || loadingType == '2') {

            let self = this;
            let promise = new Promise((resolve, reject) => {
                let http = new XMLHttpRequest();
                let url = '/catalog/handler';
                let params = typeof (p) !== 'undefined' && typeof (p) !== '' ? 'getProducts=getProducts&' + p : 'getProducts=getProducts';

                http.open('POST', url, true);
                http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                http.onload = function () {
                    if (http.status == 200) {
                        const jsonResponse = JSON.parse(http.responseText);
                        self.dataSource = jsonResponse;
                        let dataCart = self.dataSource.cart;

                        let dataProducts = self.dataSource.products;

                        if (p) {

                            urlParams = self.getUrlParams(p);

                            if (urlParams['cat']) {
                                //здесь если изначально открть окно с середины, то будет ошибка, надо исправить
                                dataProducts = dataProducts.filter(data => data.id_category == urlParams['cat']);
                            }
                            if (urlParams['sort']) {

                                if (urlParams['sort'] == '2') {
                                    dataProducts = dataProducts.sort(function (a, b) {
                                        return a.price - b.price;
                                    });
                                } else if (urlParams['sort'] == '1') {
                                    dataProducts = dataProducts.sort(function (a, b) {
                                        return b.avgstars - a.avgstars;
                                    });
                                } else if (urlParams['sort'] == '0') {
                                    dataProducts = dataProducts.sort(function (a, b) {
                                        return b.weight - a.weight;
                                    });
                                } else if (urlParams['sort'] == '4') {
                                    dataProducts = dataProducts.sort(function (a, b) {
                                        return b.price - a.price;
                                    });
                                }
                            }

                            if (urlParams['price']) {
                                let urlPrice = urlParams['price'].split(';');
                                let minPrice = Number(urlPrice[0]);
                                let maxPrice = Number(urlPrice[1]);

                                dataProducts = dataProducts.filter((data) => {
                                    return Number(Math.round(data.price)) >= minPrice && Number(Math.round(data.price)) <= maxPrice;
                                });
                            }
                        }

                        self.dataProducts = dataProducts;

                        let maxPrice = self.getExtr(dataProducts, 'max');
                        let minPrice = self.getExtr(dataProducts, 'min');

                        let elBlockSliderPrice = document.querySelector('.block-slider-price');
                        if (elBlockSliderPrice) {
                            elBlockSliderPrice.innerHTML = self.getSliderPrice(minPrice, maxPrice);

                            new Slider("input.range-track-price", {});
                        }


                        if (loadingType == '2') {
                            self.getProductsView(dataProducts, dataCart, p);
                        }

                        resolve();
                    } else {
                        reject(Error(http.statusText));
                    }
                };
                http.onerror = function () {
                    reject(Error("Network Error"));
                };
                http.send(params);
            });


            promise
                .then(
                    result => {
                        let catListElem = document.querySelector('ul.category-list-catalog');
                        if (catListElem) {
                            catListElem.innerHTML = self.getCategories(self.dataSource.categories);
                        }
                        let container = document.querySelector('.container-catalog');
                        if (container) {
                            container.style.display = 'block';
                        }

                        self.setPreloader('hide');
                    },
                    error => {
                        console.error("Failed!", error);
                        self.setPreloader('hide');
                    }
                );
        }
    }

}