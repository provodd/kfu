
function explode(delimiter, string) {	// Split a string by string
    //
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: kenneth
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

    var emptyArray = { 0: '' };

    if (arguments.length != 2
        || typeof arguments[0] == 'undefined'
        || typeof arguments[1] == 'undefined') {
        return null;
    }

    if (delimiter === ''
        || delimiter === false
        || delimiter === null) {
        return false;
    }

    if (typeof delimiter == 'function'
        || typeof delimiter == 'object'
        || typeof string == 'function'
        || typeof string == 'object') {
        return emptyArray;
    }

    if (delimiter === true) {
        delimiter = '1';
    }

    return string.toString().split(delimiter.toString());
}
document.addEventListener("DOMContentLoaded", function (event) {

    let isset = function(obj) {
        var i, max_i;
        if(obj === undefined) return false;
        for (i = 1, max_i = arguments.length; i < max_i; i++) {
            if (obj[arguments[i]] === undefined) {
                return false;
            }
            obj = obj[arguments[i]];
        }
        return true;
    };

    let elementElastic = document.getElementById("elastic");
    if (typeof (elementElastic) !== 'undefined' && elementElastic !== null) {

        document.querySelector("#elastic").oninput = function () {

            let v = this.value.trim();
            let val = this.value.trim();
            val = RegExp(val, "gi");

            var http = new XMLHttpRequest();
            var url = '/search';
            var params = 'parts=' + v;
            //http.responseType = 'json';
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onload = function() {
                console.log(http);
                var jsonResponse = JSON.parse(http.responseText);
                let productsList = '';
                let categoriesList = '';
                let images = '';
                let productImage = '';
                let categoryImage = '';

                if (!isset(jsonResponse.parts) || jsonResponse.parts !== ""){
                    jsonResponse.parts.forEach(function (e) {
                        productImage = '/app/assets/images/no-image3.png';
                        let prod = e.product_name;
                        let str = prod;
                        productsList = productsList + '<li class="text-left"><img class="img-rounded" width="50px" src="'+ productImage +'"><a class="p-3 text-sm" href="/single?id=' + e.id + '">' + prod + '<a></li>';

                    });
                }
                if (!isset(jsonResponse.categories) || jsonResponse.categories !== ""){
                    jsonResponse.categories.forEach(function (e) {
                        console.log(jsonResponse);
                        if (e.id>0){
                            productImage = '/app/assets/images/no-image3.png';
                            let prod = e.product_name;
                            let str = prod;
                            categoriesList = categoriesList + '<li class="text-left"><img class="img-rounded" width="50px" src="'+ categoryImage +'"><a class="p-3 text-sm" href="/catalog?cat=' + e.id + '">' + e.category_name + '<a></li>';
                            console.log(categoriesList);
                        }
                    });
                }
                let elasticProducts = document.querySelector('.elastic-products');
                let elasticCategories = document.querySelector('.elastic-categories');

                if (v!='' && categoriesList && categoriesList !== "null"){
                    elasticCategories.innerHTML = categoriesList;
                }else{
                    elasticCategories.innerHTML = '';
                }
                if (v!='') {
                    elasticProducts.innerHTML = productsList;
                }else{
                    elasticProducts.innerHTML = '';
                }
            };
            http.send(params);

        }
    }



});

