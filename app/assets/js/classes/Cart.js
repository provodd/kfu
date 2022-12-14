import Catalog from "./Catalog.js";
import explode from "/app/assets/js/functions/explode.js";
import setPrice from "/app/assets/js/functions/setPrice.js?ver=2";

export default class Cart extends Catalog {

    sendOrder(type) {
        let order_success = document.getElementById('result-cart');
        var http = new XMLHttpRequest();
        var url = 'cart/handler';
        var params = 'sendOrder='+type;

        //http.responseType = 'json';
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onload = function () {
            if (http.status == 200) {
                let jsonResponse = JSON.parse(http.responseText);
                if (jsonResponse.status=='ok'){
                    order_success.innerHTML = jsonResponse.text;
                }else{
                    
                }
                console.log(jsonResponse);

            }
        };
        http.onerror = function () {

        };
        http.send(params);
    }

    getCart() {
        let self = this;
        return new Promise((resolve, reject) => {
            var http = new XMLHttpRequest();
            var url = 'cart/handler';

            var params = 'getCart=getCart';
            //http.responseType = 'json';
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onload = function () {
                if (http.status == 200) {
                    
                    let jsonResponse = JSON.parse(http.responseText);
                    let result = jsonResponse.cart;
                    let items = '';
                    let price = '';
                    let productImage, images;
                    if (result.length !== 0) {
                        result.forEach(element => {
                            if (!element.product_img) {
                                images = explode(',', element.product_img);
                                productImage = "/uploads/products/" + images[0];
                            } else {
                                productImage = '/app/assets/images/no-image3.png';
                            }

                            price = Number(element.price) * Number(element.kolvo) + Number(price);
                            items = items + `
                        <div class="row border-bottom pl-3">
                            <div class="row main align-items-center">
                                <div class="col-4 mt-1 col-md-2"><img class="img-rus img-fluid" src="` + productImage + `"></div>
                                <div class="col-8 mt-1 col-md-4">
                                    <div class="row text-muted"><a href="single?id=` + element.id_product + `">` + element.product_name + `</a></div>
                                    <div class="row"></div>
                                </div>
                                <div class="col-5 mt-1 col-md-2">
                                    <a class="btn-minus" href="#" idc="` + element.id + `" idp="` + element.id_product + `">-</a>
                                    <input type="number" min="1" id="cart-item-count` + element.id_product + `" class="cart-item-count" value="` + element.kolvo + `" readonly="">
                                    <a href="#" class="btn-plus" idc="` + element.id + `" idp="` + element.id_product + `">+</a>
                                </div>
                                <div class="col-5 mt-1 col-md-3">
                                    <a class="nowrap" id="cart-item-sum` + element.id_product + `">` + setPrice(element.price * element.kolvo) + `&nbsp;&#8381;</a>
                                </div>
                                <div class="col-1 mt-1 col-md-1">
                                    <span class="close delete-cart-item" idp="` + element.id_product + `" idc="` + element.id + `">&#10005;</span>
                                </div>
                                    

                            </div>
                        </div>`;
                        });

                        if (typeof items !== 'undefined' && items != null) {
                            document.getElementById("priceCart").innerHTML = '&#8381; ' + price;
                            document.getElementById("cart-items").innerHTML = items;
                        }

                    }else{
                        
                        if (typeof document.getElementById("cart-items") !== 'undefined' && document.getElementById("cart-items") !=null) {
                            document.getElementById("cart-items").innerHTML = '';
                            location.reload();
                        }
                        
                    }
                    resolve(http.responseText);
                } else {
                    reject(Error(http.statusText));
                }

            };
            http.onerror = function () {
                //new Error("Whoops!");
                reject(Error("Network Error"));
            };
            http.send(params);
        }).then(function (response) {
            self.setPreloader('hide');
        }, function (error) {
            console.error("Failed!", error);
            self.setPreloader('hide');
        });
    }

    getCartModal(id_product) {

        let btnToCart = document.querySelector('i[idp="'+id_product+'"]').parentElement;
        btnToCart.classList.add('bg-success');
        btnToCart.classList.remove('bg-primary');
        let images, productImage;
        let modalAddedToCart = document.createElement("div");
        let data = this.dataSource.products;
        let product = data.find(x => x.id == id_product);
        modalAddedToCart.id = "modalAddedToCart";
        modalAddedToCart.classList.add('modal');
        modalAddedToCart.setAttribute("tabindex", "-1");

        if (!product.product_img) {
            images = explode(',', product.product_img);

            productImage = "/uploads/products/" + images[0];
        } else {
            productImage = '/app/assets/images/no-image3.png';
        }

        modalAddedToCart.innerHTML = `
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Товар добавлен в корзину</h5>
            <button type="button" class="close close-modal" data-dismiss="modal" id_modal="modalAddedToCart" aria-label="Close">
                <span class="close-modal" aria-hidden="true" id_modal="modalAddedToCart">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row w-100 p-3 border-topx border-bottomx">
                <div class="row w-100 pl-3 main align-items-center">
                    <div class="col-4 mt-1 col-md-2">
                        <img class="img-fluid" src="` + productImage + `">
                    </div>
                    <div class="col-8 mt-1 col-md-5">
                        <div class="row text-muted">` + product.product_name + `</div>
                    </div>

                    <div class="col-6 mt-1 col-md-3">
                        <a class="btn-minus" href="#" idp="` + product.id + `">-</a>
                        <input type="number" min="1" id="cart-item-count` + product.id + `" class="cart-item-count" value="1" readonly>
                        <a href="#" class="btn-plus" idp="` + product.id + `">+</a>
                    </div>
                    <div class="col-6 mt-1 col-md-2">
                        <a class="nowrap" id="cart-item-sum` + product.id + `">` + setPrice(product.price) + `&nbsp;&#8381;</a>
                        <span class="dnone close delete-cart-item" idp="` + product.id + `">&#10005;</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal" id_modal="modalAddedToCart" data-dismiss="modal">Продолжить покупки</button>
            <a type="button" href="cart" class="btn btn-primary">Перейти в корзину</a>
        </div>
    </div>
</div>`;

        document.body.append(modalAddedToCart);
        modalAddedToCart.style.display = 'block';
    }

    addToCart(id_product, catalogLoad) {

        let self = this;
        let promise = new Promise((resolve, reject) => {
            let http = new XMLHttpRequest();
            let url = 'cart/handler';
            let params = 'addToCart=' + id_product;

            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onload = function () {
                if (http.status == 200) {

                    let jsonResponse = JSON.parse(http.responseText);
                    self.dataSource = jsonResponse;
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
        return promise
            .then(
                result => {
                    //если нужно обновить каталог
                    if (catalogLoad == 'true') {
                        this.setPreloader('hide');
                        self.loadCatalog('2');
                    } else {
                        this.setPreloader('hide');
                        let btnToCart = document.getElementById("btn-to-cart");
                        if (typeof (btnToCart) != 'undefined' && btnToCart != null) {
                            btnToCart.innerHTML = `<a href="cart" class="btn btn-success text-light btn-block">Оформить заказ</a>`;
                        }

                    }
                },
                error => {
                    console.error("Failed!", error);
                }
            );
    };

    editCount(id_product, count, type) {
        let self = this;
        let promise = new Promise((resolve, reject) => {
            let http = new XMLHttpRequest();
            let url = 'cart/handler';
            let params = 'editCount=' + id_product + '&count=' + count;

            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onload = function () {
                if (http.status == 200) {

                    let jsonResponse = JSON.parse(http.responseText);
                    //self.dataSource = jsonResponse;

                    resolve(jsonResponse);
                } else {
                    reject(Error(http.statusText));
                }
            };
            http.onerror = function () {
                reject(Error("Network Error"));
            };
            http.send(params);
        });
        return promise
            .then(
                result => {
                    if (type) {
                        document.getElementById("priceCart").innerHTML = '&#8381; ' + setPrice(result.sumcartall);
                    }
                    document.getElementById('cart-item-sum' + id_product).innerHTML = '&#8381;&nbsp;' + setPrice(result.sumcart);
                    this.setPreloader('hide');
                },
                error => {
                    console.error("Failed!", error);
                    this.setPreloader('hide');
                }
            );

    };

    deleteCartItem(id_product) {

        let self = this;
        let promise = new Promise((resolve, reject) => {
            let http = new XMLHttpRequest();
            let url = 'cart/handler';
            let params = 'deleteItem=' + id_product;

            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onload = function () {
                if (http.status == 200) {

                    let jsonResponse = JSON.parse(http.responseText);
                    //self.dataSource = jsonResponse;

                    if (jsonResponse.status =='ok'){
                        toastr.success(jsonResponse.text,'Успешно!');
                    }else{
                        toastr.error(jsonResponse.text,'Ошибка!');
                    }
                    resolve(jsonResponse.sumcart);
                } else {
                    reject(Error(http.statusText));
                }
            };
            http.onerror = function () {
                reject(Error("Network Error"));
            };
            http.send(params);
        });
        return promise
            .then(
                result => {
                    self.getCart();
                    this.setPreloader('hide');
                },
                error => {
                    console.error("Failed!", error);
                    this.setPreloader('hide');
                }
            );

    };
}