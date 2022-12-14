<?php
namespace App\Controllers;
use provodd\base_framework\Db;


class Handler extends Controller
{
    public function index(){

        new Db();

        $products = \R::getAll('SELECT * FROM ek_products WHERE visible!="0" LIMIT 5');
        $categories = \R::getAll('SELECT id_category, COUNT(*) as Qty, cats.category_name FROM ek_products 
LEFT JOIN ek_categories as cats ON cats.id = ek_products.id_category  WHERE visible!="0" GROUP BY id_category ORDER BY Qty DESC');

        $params['title'] = 'Главная страница';
        $params['products'] = $products;
        $params['categories'] = $categories;

        parent::render('main', $params);
    }
}
