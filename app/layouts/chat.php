<!DOCTYPE html>
<html lang="en">

<head>
    <style>
    </style>
    <!-- SITE TITTLE -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Пользователи чата</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/app/assets/css/dataTables.css">
    <link rel="stylesheet" href="/app/assets/css/main.css?v=<?= time()?>">

    <script src="/node_modules/@popperjs/core/dist/umd/popper.js"></script>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/app/assets/js/jquery.js"></script>
    <script src="/app/assets/js/dataTables.js"></script>

    <?php

    ?>
    <section>
        <div class="container" id="result-cart">
            <div class="row">
                <div class="col">
                    <table id="users-table" style="width:100%">
                        <thead>
                        <tr class="d-none">
                            <th class="td-number"></th>
                            <th></th>
                            <th class="td-name">Пользователь</th>
                            <th class="td-sex">Пол</th>
                            <th>Количество сообщений</th>
                            <th>Баллы за лотерею</th>
                            <th class="d-none">Общий рейтинг</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $users = array();
                        foreach ($params['users'] as $key => $item) {
                            $user = R::findOne('victorina_rating', 'id_user=?', array($item['id_user']));
                            $users[$key] = $item;
                            $users[$key]['amount'] = $item['messages_count'] + ($user->rating ?? 0);
                        }
                        $key_values = array_column($users, 'amount');
                        array_multisort($key_values, SORT_DESC, $users);
                        foreach ($users as $k => $item) {
                          $i++;
//                            switch ($i) {
//                                case 1:
//                                    $class = 'first';
//                                    break;
//                                case 2:
//                                    $class = 'second';
//                                    break;
//                                case 3:
//                                    $class = 'third';
//                                    break;
//                                default:
//                                    $class = '';
//                            }
                            $user = R::findOne('victorina_rating', 'id_user=?', array($item['id_user']));
                            $rating = $user->rating ?? 0;
                            $class = '';
                            if ($item['is_admin'] AND $item['id_user']!=='13007909') $class = 'first';
                            ?>
                            <tr>
                                <td class="td-number">
                                    <span class="d-flex flex-row">
                                        <i class="fa fa-star <?=$class?>"></i><div>&nbsp;<?= $i ?></div>
                                    </span>
                                </td>
                                <td><img class="avatar img rounded-circle" src="<?= $item['photo'] ?>"></td>
                                <td class="td-name <?=$class?>"><a href="https://vk.com/id<?=$item['id_user']?>">
                                        <?= $item['firstname'] ?> <?= $item['lastname'] ?>
                                    </a>
                                </td>
                                <td class="td-sex"><?= $item['sex'] === '1' ? 'Ж' : 'М' ?></td>
                                <td title="Баллы за сообщения">
                                    <div class="d-flex flex-row align-items-center gap-1">
                                        <i class="fa fa-comment"></i><?= $item['messages_count'] ?>
                                    </div>
                                <td title="Баллы за викторину">
                                    <div class="d-flex flex-row align-items-center gap-1">
                                        <i class="fa fa-vimeo"></i><span><?= $rating ?></span>
                                    </div>
                                </td>
                                <td title="Общее количество баллов" class="d-none">
                                    <div class="d-flex flex-row align-items-center gap-1">
                                        <i class="fa fa-star <?=$class?>"></i><span><?= $item['amount'] ?></span>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function () {
            $('#users-table').DataTable({
                layout: {
                    topStart: null,
                    bottomStart: null
                },
                "bSort": false,
                pagingType: 'simple_numbers',
                language: {
                    "processing": "Подождите...",
                    "search": "Поиск:",
                    "lengthMenu": "Показать _MENU_",
                    "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "Назад",
                        "next": "Вперед",
                        "last": "Последняя"
                    },
                    "aria": {
                        "sortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sortDescending": ": активировать для сортировки столбца по убыванию"
                    }
                },
            });
        });
    </script>
    </body>
</html>