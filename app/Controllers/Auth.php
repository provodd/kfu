<?php

namespace App\Controllers;

use provodd\base_framework\Db;
use App\Controllers\Helper;

class Auth extends Controller
{
    public function __construct()
    {
        parent::__construct();
        new Db();
    }
    public function logout()
    {
        unset($_SESSION['logged_user']);
        header('Location: /');
    }
    public function handler()
    {
        if (isset($_POST)) {
            $data = $_POST;

            //регистрация
            if (isset($data['reg-fio']) or isset($data['reg-email']) or isset($data['reg-phone'])) {


                $fio = $data['reg-fio'];
                $email = $data['reg-email'];
                $phone = $data['reg-phone'];
                $token = md5($email . time());

                if (empty($email)) {
                    $errors[] = 'Введите Email';
                }
                if (empty($phone)) {

                    $errors[] = 'Введите номер телефона';
                } else {
                    if (!Helper::instance()->isValidTelephoneNumber($phone)) {
                        $errors[] = 'Введите корректный номер телефона';
                    }
                }
                if ($user = \R::findOne('users', "email = ?", [$email])) {
                    $errors[] = 'Пользователь c таким email уже существует';
                }

                if (empty($errors)) {

                    $user = \R::xdispense('users');
                    $user->fio = $fio;
                    $user->email = $email;
                    $user->phone = Helper::instance()->normalizeTelephoneNumber($phone);
                    $user->date = date('Y-m-d H:i:s');
                    $user->color = '';
                    $user->avatar = NULL;
                    $user->password =  password_hash($data['reg-password'], PASSWORD_DEFAULT);
                    $user->status = '1';
                    $user->email_status = '0';
                    $user->token = $token;
                    $id = \R::store($user);

                    if ($id) {
                        $result['fio'] = $fio;
                        $result['email'] = $email;
                        $result['status'] = 'ok';
                        $result['res'] = 'Пользователь добавлен';
                    } else {
                        $result['status'] = 'error';
                        $result['res'] = 'Не удалось добавить пользователя';
                    }

                } else {
                    $result['res'] = $errors[0];
                    $result['status'] = 'error';
                }

            }
            //авторизация
            if (isset($data['login']) or isset($data['login_password'])) {

                if (isset($data['login']) && isset($data['password'])) {

                    $email = trim($_POST['login']);
                    $pass = trim($_POST['password']);
                    $errors = [];

                    if (empty($email)) {
                        $errors[] = 'Введите Email';
                    }
                    if (empty($pass)) {
                        $errors[] = 'Введите пароль';
                    }
                    if (is_null($user = \R::findOne('users', "email = ?", [$email]))) {
                        $errors[] = 'Пользователь не найден';
                    }else{
                        if (!password_verify($pass, $user->password)) {
                            $errors[] = 'Неверный пароль';
                        }
                    }

                    if (empty($errors)) {

                        $_SESSION['logged_user'] = $user;
                        $hash = $_COOKIE['user_hash'];
                        $cart = \R::getAll("SELECT * FROM cart WHERE user_hash='$hash'");
                        if (isset($cart)) {
                            foreach ($cart as $item) {
                                \R::exec("UPDATE cart SET id_user ={$user->id} WHERE id={$item['id']}");
                            }
                        }
                        //header('Location: /index.php');
                        $result['status'] = 'ok';
                        $result['text'] = 'Успешная аунтентификация';
                    } else {
                        $result['status'] = 'error';
                        $result['text'] = $errors[0];
                    }
                }

            }
            echo isset($result) ? json_encode($result) : '';
        }
    }
}
