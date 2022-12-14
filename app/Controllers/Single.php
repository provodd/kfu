<?php
namespace App\Controllers;
use provodd\base_framework\Db;


class Single extends Controller
{
    public function __construct()
    {
        parent::__construct();
        new Db();
    }
    public function index(){

        parent::render('single', array());
    }
    public function review(){
        if (isset($_POST)){
            $data = $_POST;
            if (isset($data['text-review'])) {

                $rew = \R::findOne('review', 'id_product=? AND id_user=?', array($data['id_product'], $_SESSION['logged_user']['id']));
                if ($rew) {
                    $result['status'] = 'error';
                    $result['text'] = 'Вы уже добавили отзыв';
                } else {
                    if ($_SESSION['logged_user']) {
                        $id_user = $_SESSION['logged_user']['id'];
                    } else {
                        $id_user = NULL;
                    }
                    $date = date("Y-m-d H:i:s");
                    $view = \R::xdispense('review');
                    $view->id_user = $id_user;
                    $view->text = $data['text-review'];
                    $view->stars = $data['stars'];
                    $view->datetime = $date;
                    $view->timestamp = $date;
                    $view->id_product = $data['id_product'];
                    $id = \R::store($view);

                    if ($id) {

                        $reviews = \R::getAll("SELECT * FROM review WHERE id_product = {$data['id_product']}");
                        if ($reviews) {
                            foreach ($reviews as $review) {
                                $user = \R::findOne('users', 'id=?', array($review['id_user']));

                                if (isset($user->avatar)) {
                                    $av =  '<img src="uploads/avatar/' . $user->avatar . '" alt="avatar">';
                                } else {
                                    $av = '<img src="/app/assets/images/avatar/unnamed.jpg" alt="avatar">';
                                };


                                $a = 5;
                                $b = intval($review['stars']);
                                $c = $a - $b;
                                $stars = '';
                                if ($b) {
                                    for ($i = 1; $i <= $b; $i++) {
                                        $stars .= '<li class="list-inline-item">
                                <i class="fa fa-star"></i>
                            </li>';
                                    }
                                }
                                if ($c) {
                                    for ($j = 1; $j <= $c; $j++) {
                                        $stars .= '<li class="list-inline-item">
                                <i class="fa fa-star-o"></i>
                            </li>';
                                    }
                                }


                                $rew .= '<div class="media">
                       '.$av.'
                        <div class="media-body">
                            <div class="ratings">
                                <ul class="list-inline">
                                    ' . $stars . '
                                </ul>
                            </div>
                            <div class="name">
                                <h5>' . $user->fio . '</h5>
                            </div>
                            <div class="date">
                                <p> ' . $review['datetime'] . ' </p>
                            </div>
                            <div class="review-comment">
                                <p>
                                ' . $review['text'] . '
                                </p>
                            </div>
                        </div>
                    </div>';
                            }
                        }
                        $result['status'] = 'ok';
                        $result['rew'] = $rew;
                        $result['text'] = 'Отзыв добавлен';
                    } else {
                        $result['status'] = 'error';
                        $result['text'] = 'Не удалось добавить отзыв';
                    }
                }
                echo json_encode($result);
            }
        }
    }
}
