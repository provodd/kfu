<?php if ($_SESSION['logged_user']) {
    $user = $params[0] ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Профиль</title>

        <?php include 'components/header.php'; ?>
        <?php include 'components/navbar.php'; ?>

        <section class="user-profile section">
            <div id="preloader" class="hidden"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-10 offset-md-1 col-lg-3 offset-lg-0">
                        <div class="sidebar">
                            <div class="widget user">
                                <div class="image d-flex justify-content-center">
                                    <?php
                                    $avatar = !empty($_SESSION['logged_user']['avatar']) ? $_SESSION['logged_user']['avatar'] : NULL;
                                    if ($avatar) {
                                        echo "<img class='profile-user-img img-fluid img-circle' src='/app/assets/images/avatar/$avatar' alt='Аватарка'>";
                                    } else {
                                        echo "<img class='profile-user-img img-fluid img-circle' src='/app/assets/images/avatar/unnamed.jpg' alt='Аватарка'>";
                                    }
                                    ?>
                                </div>
                                <h5 class="text-center"><?= $user->fio ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">

                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="widget personal-info">
                                    <h3 class="widget-header user">Персональная информация</h3>
                                    <form action="#" name="form-personal-info" id="form-personal-info">

                                        <div class="form-group">
                                            <label for="first-name">ФИО</label>
                                            <input name="first-name" type="text" class="form-control" id="first-name"
                                                   value="<?= $user->fio ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="current-email">Email</label>
                                            <input name="current-email" type="email" class="form-control"
                                                   id="current-email" value="<?= $user->email ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="city-name">Город</label>
                                            <select class="form-control w-100 ignore" id="city-name" name="city-name">
                                                <?php
                                                $goroda = R::getAll("SELECT * FROM goroda");
                                                if (!isset($user->id_gorod)) {
                                                    echo '<option selected value="0">Не выбрано</option>';
                                                }

                                                foreach ($goroda as $val) {
                                                    $selected = '';
                                                    if (isset($user->id_gorod)) {
                                                        if ($user->id_gorod == $val['id']) {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                    ?>
                                                    <option <?= $selected ?> value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="zip-code">Индекс</label>
                                            <input type="text" class="form-control" name="zip-code" id="zip-code"
                                                   value="<?= $user->zip ?>">
                                        </div>
                                        <div class="btn btn-transparent save-personal-info">Сохранить</div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="widget change-password">
                                    <h3 class="widget-header user">Изменить пароль</h3>
                                    <form action="#" name="form-password">

                                        <div class="form-group">
                                            <label for="current-password">Текущий пароль</label>
                                            <input type="password" class="form-control" id="password_old"
                                                   name="password_old">
                                        </div>

                                        <div class="form-group">
                                            <label for="new-password">Новый пароль</label>
                                            <input type="password" class="form-control" id="password_new_1"
                                                   name="password_new_1">
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm-password">Подтвердите новый пароль</label>
                                            <input type="password" class="form-control" id="password_new_2"
                                                   name="password_new_2">
                                        </div>

                                        <div class="btn btn-transparent btn-change-password">Сохранить</div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php include 'components/footer.php'; ?>

        <script type="module" defer>

            $(document).ready(function () {
                $('#city-name').select2();
            });

            document.addEventListener("DOMContentLoaded", function (event) {

                document.onclick = function (event) {

                    let target = event.target;

                    if (target.classList.contains('save-personal-info')) {

                        let Form = document.forms['form-personal-info'];
                        let data = new FormData(Form);

                        sendAjaxForm(data, true, '/profile/edit');

                    } else if (target.classList.contains('btn-change-password')) {

                        let Form = document.forms['form-password'];
                        let data = new FormData(Form);
                        sendAjaxForm(data, true, '/profile/edit/password');

                    }
                }
            });
        </script>
        </body>

    </html>
<?php } else {
    header("Location: /");
} ?>