<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg navbar-light navigation">
                    <a class="brand-logo" href="/"><i class="fa fa-home"></i></a>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto main-nav ">
                            <li class="nav-item">
                                <a class="nav-link" href="/contacts">Контакты</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/catalog">Каталог</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/cart">Корзина</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto mt-10">
                            <?php
                            if (isset($_SESSION['logged_user'])) {
                                ?>
                                <li class="nav-item dropdown dropdown-slide">
                                    <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <?= $_SESSION['logged_user']['fio'] ?>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="orders">Заказы</a>
                                        <a class="dropdown-item" href="profile">Профиль</a>
                                        <a class="dropdown-item" href="auth/logout">Выход</a>
                                    </div>
                                </li>
                            <?php } else {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link login-button" href="">Войти</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white reg-button add-button" href=""> Регистрация</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>