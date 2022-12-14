<?php

namespace App\Controllers;

use provodd\base_framework\Db;


class Contacts extends Controller
{
    public function __construct()
    {
        parent::__construct();
        new Db();
    }

    public function index()
    {
        parent::render('contacts', array());
    }

    public function feedback()
    {
        if (isset($_POST)) {
            $data = $_POST;
            //feedback
            if (isset($data['feed-name'])) {

                if ($_SESSION['logged_user']) {
                    $id_user = $_SESSION['logged_user']['id'];
                } else {
                    $id_user = NULL;
                }
                if ($_COOKIE['user_hash']) {
                    $user_hash = $_COOKIE['user_hash'];
                } else {
                    $user_hash = NULL;
                }
                $feed = \R::xdispense('feedback');
                $feed->name = $data['feed-name'];
                $feed->email = $data['feed-email'];
                $feed->id_type = $data['feed-category'];
                $feed->text = $data['feed-message'];
                $feed->id_user = $id_user;
                $feed->hash_user = $user_hash;
                $feed->datetime = date("Y-m-d H:i:s");
                $id = \R::store($feed);
                if ($id) {
                    //$user = R::findOne('users','id=?', array($id));
                    $result['status'] = 'ok';
                    $result['text'] = 'Сообщение отправлено';
                    $result['user'] = $data['feed-name'];
                } else {
                    $result['status'] = 'error';
                    $result['text'] = 'Не удалось отправить сообщение';
                }

                echo json_encode($result);
            }
        }
    }

}
