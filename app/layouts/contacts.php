<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Контакты">
    <title>Контакты</title>

    <?php include 'components/header.php'; ?>
    <?php include 'components/navbar.php'; ?>

    <header class="bg-dark p-3">
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="d-flex h-100 text-center align-items-center">
                <div class="w-100 text-white">
                    <h1 class="display-3 text-light" style="font-family:  Nunito Sans, sans-serif;"><a class="text-primary">К</a>онтакты</h1>
                </div>
            </div>
        </div>
    </header>

    <section class="page-title d-none">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <h3>О компании</h3>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="contact-us-content p-4">
                        <h5>Обратная связь</h5>
                        <h1 class="pt-3 d-none">Обратная связь</h1>
                        <p class="pt-3 pb-5">Напишите или позвоните нам, если у вас есть вопрос.</p>
                        <h5>Адрес компании</h5>
                        <p class="mt-3">423827, РТ, г. Набережные Челны, пр-т. Автозаводский, д. 2</p>
                        <p>тел.: <a class="text-primary">+7 (987) 654 3210</a></p>
                    </div>
                </div>
                <div class="col-md-6" id="feed-form">

                    <form action="contacts/feedback" method="POST" id="form-feedback" name="form-feedback">
                        <fieldset class="p-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 py-2">
                                        <input name="feed-name" type="text" placeholder="Имя *" class="form-control" required>
                                    </div>
                                    <div class="col-lg-6 pt-2">
                                        <input name="feed-email" type="email" placeholder="Email *" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <select name="feed-category" id="" class="form-control w-100">
                                <option value="0">Категория</option>
                                <option value="2">Запрос информации</option>
                                <option value="3">Благодарность</option>
                                <option value="4">Претензия</option>
                                <option value="5">Жалобы на обслуживание, товары, услуги</option>
                                <option value="6">Проблемы с работой сайта</option>
                            </select>
                            <textarea name="feed-message" id="" placeholder="Сообщение *" class="border w-100 p-3 mt-3 mt-lg-4"></textarea>
                            <div class="btn-group">
                                <div class="btn btn-primary mt-2 float-right send-feedback">Отправить</div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <?php include 'components/footer.php'; ?>

    </body>

</html>