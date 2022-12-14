<?php
namespace App\Controllers;
use provodd\base_framework\Db;


class User extends Controller
{
    public function __construct()
    {
        parent::__construct();
        new Db();
    }

    public function index(){
        $user = \R::findOne('users', 'id=?', array($_SESSION['logged_user']['id']));
        parent::render('profile', array($user));
    }
    public function edit(){
        //изменение персональных данных
        if (isset($_POST)){
            $data = $_POST;
            if (isset($data['first-name'])) {
                $users = \R::load('users', $_SESSION['logged_user']['id']);
                $users->fio = $data['first-name'];
                $users->email = $data['current-email'];
                $users->id_gorod = $data['city-name'];
                $users->zip = $data['zip-code'];
                $id = \R::store($users);
                if ($id) {
                    $result['status'] = 'ok';
                    $result['text'] = 'Данные сохранены';
                } else {
                    $result['status'] = 'error';
                    $result['text'] = 'Не удалось сохранить информацию';
                }

                echo json_encode($result);
            }
        }
    }
    public function password(){
        if (isset($_POST)){
            $data = $_POST;
            //изменение пароля
            if (isset($data['password_old'])) {
                $user_logged = $_SESSION['logged_user'];
                if (trim($data['password_old']) == '') {
                    $errors[] = 'Введите старый пароль!';
                }

                if (trim($data['password_new_1']) == '') {
                    $errors[] = 'Введите новый пароль!';
                }

                if (trim($data['password_new_2']) == '') {
                    $errors[] = 'Введите повторно новый пароль!';
                }

                if ($data['password_new_2'] !== $data['password_new_1']) {
                    $errors[] = 'Повторный пароль введен неверно!';
                }

                if (empty($errors)) {
                    $user = \R::findOne('users', 'id = ?', [$user_logged->id]);
                    if ($user) {
                        if (password_verify($data['password_old'], $user->password)) {
                            $user = \R::load('users', $user->id);
                            $user->password = password_hash(
                                $data['password_new_1'],
                                PASSWORD_DEFAULT
                            );
                            $id = \R::store($user);
                            $user_logged->password = $user->password;
                        } else {
                            $errors[] = 'Старый пароль введен неверно!';
                        }
                    }
                }

                if (isset($errors) AND !empty($errors)){
                    $error = array_shift($errors);
                    $result['status'] = 'error';
                    $result['text'] = $error;
                } else {
                    $result['status'] = 'ok';
                    $result['text'] = 'Пароль изменен';
                }
                echo json_encode($result);
            }
        }
    }
}
