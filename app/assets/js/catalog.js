import addParameter from "./functions/addParameter.js?ver=2";
import setPrice from "./functions/setPrice.js?ver=2";
import delParameter from "./functions/delParameter.js?ver=2";
import setLocation from "./functions/setLocation.js?ver=2";
import setPreloader from "./functions/setPreloader.js?ver=2";
import Catalog from "./classes/Catalog.js?ver=2";
import Cart from "./classes/Cart.js?ver=2";


let dataSource = {};
let catalog = new Catalog(dataSource);
window.catalog = catalog;

//loadCatalog
//0-none ajax, 3-none ajax, 4 - none ajax, select category
//1-with ajax none view, 2-with ajax

let inProgress = true;

catalog.loadCatalog('2');

inProgress = false;

$(window).scroll(function () {

    /* Если высота окна + высота прокрутки больше или равны высоте всего документа и ajax-запрос в настоящий момент не выполняется, то запускаем ajax-запрос */
    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200 && !inProgress) {
        //catalog.setPreloader('show');
        catalog.loadCatalog('0');
    }
});
document.addEventListener("DOMContentLoaded", function (event) {

    $('.catalog-sort').change(function(){
        setPreloader('show');
        setTimeout(()=>{
            let baseUrl = window.location.href;
            baseUrl = addParameter(baseUrl, 'sort', $(this).val());
            setLocation(baseUrl);
            
            catalog.setPreloader('show');
            catalog.startFrom = '0';
            catalog.dataView = [];
            catalog.loadCatalog('3')
        }, 250);
    });

    let priceSlider = $("input.range-track-price").slider();

    $(document).on("slide", priceSlider, function(slideEvt) {

        $('.value').text('₽ ' + setPrice(slideEvt.value[0]) + ' - ' + '₽ ' + setPrice(slideEvt.value[1]));

        let baseUrl = window.location.href;
        baseUrl = addParameter(baseUrl, 'price', slideEvt.value[0]+'%3B'+slideEvt.value[1]);
        setLocation(baseUrl);

        catalog.setPreloader('show');
        catalog.startFrom = '0';
        catalog.dataView = [];
        catalog.loadCatalog('3');
        
    });
    document.onclick = function (event) {

        let baseUrl = window.location.href;
        let target = event.target;

        //если нажали на категорию
        if (target.classList.contains("category_link")) {

            event.preventDefault();
            catalog.setPreloader('show');

            setTimeout(()=>{
                let categoryLink = event.target.getAttribute('href');

                if (categoryLink) {
                    baseUrl = delParameter(baseUrl, 'price');
                    baseUrl = addParameter(baseUrl, 'cat', categoryLink.split('=')[1]);
                }else{
                    baseUrl = baseUrl.split("?")[0];
                }
                setLocation(baseUrl);
                catalog.startFrom = '0';
                catalog.dataView = [];
                catalog.loadCatalog('4');
            },250);

            //если нажали на добавление в корзину
        } else if (target.classList.contains("add-to-cart")) {
            event.preventDefault();

            setPreloader('show');

            let idp = target.getAttribute('idp');
            let cart = new Cart(catalog.dataSource);

            cart.addToCart(idp, 'true');
            cart.getCartModal(idp);
            //если нажали на удаление из корзины
        } else if (target.classList.contains("delete-cart-item")) {

            // setPreloader('show');

            // let idp = target.getAttribute('idp');
            // let cart = new Cart(catalog.dataSource);

            // cart.deleteFromCart(idp, 'true');
            // cart.getCartModal(idp);

        } else if (target.classList.contains("btn-plus") || target.classList.contains("btn-minus")) {
            setPreloader('show');
            let id_product = target.getAttribute('idp');

            let inputValue = Number(document.getElementById('cart-item-count' + id_product).value);

            if (target.classList.contains("btn-plus")) {
                document.getElementById('cart-item-count' + id_product).value = inputValue + 1;
            } else if (target.classList.contains("btn-minus")) {
                document.getElementById('cart-item-count' + id_product).value = inputValue - 1;
            }

            setPreloader('hide');

            let val = Number(document.getElementById('cart-item-count' + id_product).value);


            let cart = new Cart(catalog.dataSource);
            cart.editCount(id_product, val)

        }
    }
});