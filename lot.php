<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $db_con = get_connect($database);
    $item = get_item_by_id($db_con, $id);
    $categories = get_categories($db_con);

    if($item['title'] !== null) {

        $content = include_template('lot.php', [
            'user_name' => 'Игорь',
            'categories' => $categories,
            'lot'        => $item,
            'is_auth' => $is_auth,
            'user_name' => $username
        ]);
    } else {
        http_response_code(404);
        $content = '';
    }

    print($content);
} else {
    http_response_code(404);
    $content = '';
}

