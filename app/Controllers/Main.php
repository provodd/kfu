<?php
namespace App\Controllers;

use provodd\base_framework\Db;

class Main extends Controller
{
    public function __construct()
    {
        parent::__construct();
        new Db();
    }
    public function index(){

        $products = \R::getAll('SELECT * FROM ek_products WHERE visible!="0" LIMIT 5');
        $categories = \R::getAll('SELECT id_category, COUNT(*) as Qty, cats.category_name FROM ek_products 
LEFT JOIN ek_categories as cats ON cats.id = ek_products.id_category  WHERE visible!="0" GROUP BY id_category ORDER BY Qty DESC');

        $params['title'] = 'Главная страница';
        $params['products'] = $products;
        $params['categories'] = $categories;

        parent::render('main', $params);
    }

    public function orders(){
        parent::render('orders', array());
    }
    public function search(){
        $name = $_POST['parts'];
        $rows =  \R::getAll("SELECT * FROM ek_products WHERE product_name LIKE '%{$name}%' LIMIT 5");
        $arr = array();
        $arr2 = array();
        foreach ($rows as $key => $val) {
            $prod =  \R::findOne('ek_products', 'id=?', array($val['id']));
            $cat =  \R::findOne('ek_categories', 'id=?', array($prod->id_category));
            if (isset($val) AND isset($val[$key])){
                $array[$key] = $val[$key];
            }

            $arr[] = $prod;
            if ($cat->id != '1'){
                $array2['id'] = $cat->id;
                $array2['category_name'] = $cat->category_name;

                if (!in_array($array2, $arr2)) {
                    $arr2[] = $array2;
                }
            }
        }
        if (isset($arr2) AND is_array($arr2)){
            array_unique($arr2, SORT_REGULAR);
        }
        $result['parts'] = $arr ?? "";
        $result['categories'] = $arr2 ?? "";
        echo json_encode($result);
    }
}
