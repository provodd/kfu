<?php
namespace App\Controllers;

use provodd\base_framework\Db;

class Controller
{
    public $base = '';
    public $route;
    //public $user_hash;

    function __construct()
    {
        $this->route = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if (empty($_COOKIE['user_hash'])) {
            $md1 = md5(date('l jS \of F Y h:i:s A'));
            setcookie("user_hash", $md1, time() + 36000,'/');
        }
        new Db();
        //$this->user_hash = $_COOKIE['user_hash'];
    }

    public function render($view, $params = array())
    {
        require $_SERVER['DOCUMENT_ROOT'] . $this->base . '/app/layouts/' . $view . '.php';
    }
}