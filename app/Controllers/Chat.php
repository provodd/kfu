<?php

namespace App\Controllers;
use provodd\base_framework\Db;

class Chat extends Controller
{
    public function index()
    {
        $params['title'] = 'Чат';
//        $users = \R::getAll("SELECT c.first_name as firstname, c.last_name as lastname, c.*, v.rating as rating FROM chat_users as c
//        CROSS JOIN victorina_rating AS v ON v.id_user = c.id_user
//        WHERE c.id_group = 83552915
//        ORDER BY c.messages_count DESC"
//        );
        $users = \R::getAll("SELECT c.first_name as firstname, c.last_name as lastname, c.* FROM chat_users as c
        WHERE c.id_group = 83552915
        ORDER BY c.messages_count DESC"
        );
        $params['users'] = $users;

        parent::render('chat', $params);
    }
}
