<?php
namespace App;
header('Content-Type: text/html; charset=utf-8');

use provodd\base_framework\Providers\Router;

require_once __DIR__ .'/vendor/autoload.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$route_list = [
    "/" => ['controller' => 'Main', 'action' => 'index'],
    "/orders" => ['controller' => 'Main', 'action' => 'orders'],
    "/search" => ['controller' => 'Main', 'action' => 'search'],
    "/profile" => ['controller' => 'User', 'action' => 'index'],
    "/profile/edit" => ['controller' => 'User', 'action' => 'edit'],
    "/profile/edit/password" => ['controller' => 'User', 'action' => 'password'],
    '/cart' => ['controller' => 'Cart', 'action' => 'index'],
    '/catalog' => ['controller' => 'Catalog', 'action' => 'index'],
    '/catalog/handler' => ['controller' => 'Catalog', 'action' => 'handler'],
    '/cart/handler' => ['controller' => 'Cart', 'action' => 'handler'],
    '/single' => ['controller' => 'Single', 'action' => 'index'],
    '/review' => ['controller' => 'Single', 'action' => 'review'],
    '/contacts' => ['controller' => 'Contacts', 'action' => 'index'],
    '/contacts/feedback' => ['controller' => 'Contacts', 'action' => 'feedback'],
    '/auth/handler' => ['controller' => 'Auth', 'action' => 'handler'],
    '/auth/logout' => ['controller' => 'Auth', 'action' => 'logout'],
];

$router = new Router($route_list);
$router->start();





