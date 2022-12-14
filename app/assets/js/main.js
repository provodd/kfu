toastr.options = {
    closeButton: false,
    debug: false,
    newestOnTop: false,
    progressBar: false,
    positionClass: "toast-bottom-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

function setPreloader(type) {

    let preloaderEl = document.getElementById('preloader');
    if (type == 'hide') {
        preloaderEl.classList.add('hidden');
        preloaderEl.classList.remove('visible');
    } else {
        preloaderEl.classList.add('visible');
        preloaderEl.classList.remove('hidden');
    }
}

function isEmpty(str) {
    return !str || str.length === 0;
}

function sendAjaxForm(data, notice, baseUrl) {
    baseUrl = typeof baseUrl !== "undefined" ? baseUrl : 'https://alfinna.ru/';
    let errors;
    let jsonResponse;
    let http = new XMLHttpRequest();
    let url = baseUrl;
    http.open("POST", url, true);
    //http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onload = function () {
        if (http.status == 200) {
            jsonResponse = JSON.parse(http.responseText);
            if (jsonResponse.status == "ok") {
                if (notice) {
                    if (notice == "feedback") {
                        let feedForm = document.getElementById("feed-form");
                        feedForm.innerHTML =
                            `
                        <div class="container">
                        <div class="row text-center">
                            <div class="col-sm-12 col-sm-offset-3">
                                <br>
                                <h1 style=""><i class="fa fa-check-circle text-success"></i></h1>
                                <h3>Уважаемый ` +
                            jsonResponse.user +
                            `</h3>
                                <p style="font-size:20px;">Спасибо за ваше обращение. Ваш запрос принят и отправлен в обработку.</p>
                                <a href="index.php" class="btn btn-primary">    Перейти на главную    </a>
                                <br><br>
                            </div>
                        </div>
                        </div>
                        `;
                    } else if (notice == "review") {
                        let rewiewBlock = document.getElementById("product-review");
                        rewiewBlock.innerHTML = jsonResponse.rew;
                    } else if (notice == "reg-end") {
                        let regEndBlock = document.getElementById("reg-end-result");
                        regEndBlock.innerHTML = jsonResponse.div;
                    } else if (notice == "lockscreen-login") {
                        setTimeout(() => {
                            location.reload();
                        }, 400);
                    } else if (notice == "user-verification") {
                        setPreloader('hide');
                    }
                    toastr.success(jsonResponse.text, "Успешно!");
                }
            } else {
                if (notice) {
                    if (notice == "user-verification") {
                        setPreloader('hide');
                    }
                    toastr.error(jsonResponse.text, "Ошибка!");
                }
            }
        } else {
            errors = "error1";
            if (notice) {
                if (notice == "user-verification") {
                    setPreloader('hide');
                }
                toastr.error(jsonResponse.text, "Ошибка!");
            }
        }
    };
    http.onerror = function () {
        errors = "error2";
        if (notice) {
            if (notice == "user-verification") {
                setPreloader('hide');
            }
            toastr.error(jsonResponse.text, "Ошибка!");
        }
    };
    http.send(data);
}

document.addEventListener("DOMContentLoaded", function (event) {
    //события клик
    window.onclick = function (event) {
        let target = event.target;

        if (target.classList.contains("login-button")) {
            event.preventDefault();

            var modalLogin = document.createElement("div");
            modalLogin.id = "modalLogin";
            modalLogin.classList.add("modal");
            modalLogin.setAttribute("tabindex", "-1");

            modalLogin.innerHTML = `
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Вход</h5>
            <button type="button" class="close close-modal" id_modal="modalLogin" data-dismiss="modal" aria-label="Close">
                <span class="close-modal" aria-hidden="true" id_modal="modalLogin">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="form-login" enctype="multipart/form-data">
                <fieldset class="p-4">
                    <input name="login" type="text" placeholder="email" class="border p-3 w-100 my-2">
                    <input name="password" type="password" placeholder="Пароль" class="border p-3 w-100 my-2">
                    
                    <button type="submit" class="login-submit btn btn-primary btn-block mt-3">Войти</button>
                    Еще нет аккаунта? <a class="mt-3 d-inline-block text-primary reg-button" href="">Зарегистрироваться</a>
                </fieldset>
            </form>
        </div>
    </div>
</div>`;

            var modalReg = document.getElementById("modalReg");
            if (modalReg) {
                modalReg.remove();
            }

            document.body.append(modalLogin);
            modalLogin.style.display = "block";
            //нажатие на кнопку регистрации
        } else if (target.classList.contains("reg-button")) {
            event.preventDefault();

            var modal = document.createElement("div");
            modal.id = "modalReg";
            modal.classList.add("modal");
            modal.setAttribute("tabindex", "-1");

            modal.innerHTML = `
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Создание учетной записи</h5>
            <button type="button" class="close close-modal" id_modal="modalReg" data-dismiss="modal" aria-label="Close">
                <span class="close-modal" aria-hidden="true" id_modal="modalReg">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="modal_body_reg">
            
            <form id="form-reg" enctype="multipart/form-data">
                <fieldset class="p-4">
                    <input name="reg-fio" type="text" placeholder="Фамилия Имя Отчество" class="border p-3 w-100 my-2">
                    <input name="reg-email" type="email" required placeholder="Почтовый ящик" class="border p-3 w-100 my-2">
                    <input id="reg-phone" name="reg-phone" type="text" placeholder="Телефон" class="border p-3 w-100 my-2">
                    <input id="reg-password" name="reg-password" type="password" placeholder="Пароль" class="border p-3 w-100 my-2">
                    <p></p>
                    <button class="btn btn-dark btn-block reg-submit mt-3">Зарегистрироваться</button>
                </fieldset>
            </form>
            <p class="text-center">Уже есть аккаунт? <a href="" class="login-button">Вход</a></p>
        </div>
    </div>
</div>`;
            document.body.append(modal);

            var phoneSelector = document.getElementById("reg-phone");

            var im = new Inputmask("+7 (999) 999 9999");
            im.mask(phoneSelector);

            var modalLogin = document.getElementById("modalLogin");

            if (modalLogin) {
                modalLogin.remove();
            }

            modal.style.display = "block";
            //регистрация
        } else if (target.classList.contains("reg-submit")) {
            event.preventDefault();

            function validateEmail(email) {
                let re = /\S+@\S+\.\S+/;
                return re.test(email);
            }

            function validateFio(fio) {
                let re = /^[А-Яа-яё\s]+$/i;
                return re.test(fio);
            }

            let errors = [];
            let regEmail = document.getElementsByName("reg-email")[0].value;
            let regFio = document.getElementsByName("reg-fio")[0].value;

            if (!validateEmail(regEmail)) {
                errors.push("Укажите верный email");
            }
            if (!validateFio(regFio)) {
                errors.push("Укажите верное ФИО на кириллице");
            }
            if (errors.length !== 0) {
                toastr.error(errors[0], "Ошибка!");
            } else {
                let form = document.querySelector("#form-reg");
                let data = new FormData(form);
                var http = new XMLHttpRequest();
                var url = "auth/handler";

                http.open("POST", url, true);
                http.onload = function () {
                    if (http.status == 200) {
                        var jsonResponse = JSON.parse(http.responseText);
                        console.log(jsonResponse);
                        if (jsonResponse.status == "ok") {
                            document.getElementById("modal_body_reg").innerHTML =
                                `
                        <div class="container">
                        <div class="row text-center">
                            <div class="col-sm-12 col-sm-offset-3">
                                <br>
                                <h1 style=""></h1>
                                <h3 style="color: #666;"><i class="fa fa-check-circle text-success d-none"></i>Спасибо за регистрацию</h3>
                                <p class="text-left" style="font-size: 16px;"> Аккаунт активирован. Для входа 
                                в аккаунт используйте указанный адрес электронной почты <a href="#">` +
                                jsonResponse.email +
                                `</a></p>
                                <a href="/" class="btn btn-primary mt-3">    Перейти на главную    </a>
                                <br><br>
                            </div>
                        </div>
                        </div>
                        `;
                        } else {
                            toastr.error(jsonResponse.res, "Ошибка!");
                        }
                    }
                };
                http.onerror = function () {
                };
                http.send(data);
            }
            //завершение регистрации
        } else if (target.classList.contains("reg-end-submit")) {
            event.preventDefault();
            let form = document.querySelector("#setPassword");
            let data = new FormData(form);
            sendAjaxForm(data, "reg-end");
        }
        //нажатие на кнопку авторизации
        else if (target.classList.contains("login-submit")) {
            event.preventDefault();
            let form = document.querySelector("#form-login");
            let data = new FormData(form);
            var http = new XMLHttpRequest();
            var url = "auth/handler";

            http.open("POST", url, true);
            http.onload = function () {
                if (http.status == 200) {
                    var jsonResponse = JSON.parse(http.responseText);
                    if (jsonResponse.status == "ok") {
                        toastr.success(jsonResponse.text, "Успешно!");
                        setTimeout(() => {
                            window.location.reload();
                        }, 400);
                    } else {
                        toastr.error(jsonResponse.text, "Ошибка!");
                    }
                }
            };
            http.onerror = function () {
            };
            http.send(data);

            //закрытие модального окна
        } else if (target.classList.contains("close-modal")) {
            let id_modal = target.getAttribute("id_modal");
            let targetModal = document.querySelector("#" + id_modal);
            targetModal.remove();
            //обратная связь
        } else if (target.classList.contains("send-feedback")) {
            event.preventDefault();
            let form = document.querySelector("#form-feedback");
            let data = new FormData(form);
            sendAjaxForm(data, "feedback", 'contacts/feedback');
        } //отзывы
        else if (target.classList.contains("send-review")) {
            let id_product = target.getAttribute("idp");
            let stars = document.getElementsByClassName("review-stars")[0].childNodes;
            let i = 0;

            stars.forEach((element) => {
                let attr = element.getAttribute("class");
                if (attr == "fa-star fa") {
                    i = i + 1;
                }
            });

            event.preventDefault();
            let form = document.querySelector("#form-review");
            let data = new FormData(form);
            data.append("stars", i);
            data.append("id_product", id_product);
            sendAjaxForm(data, "review", '/review');
        }
    };

});
