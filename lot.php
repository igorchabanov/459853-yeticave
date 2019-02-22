<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $item = get_item_by_id($db_con, $id);

    if ($item !== null) {

        $content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $item,
        ]);

        $layout_content = include_template('layout.php', [
            'page_content' => $content,
            'meta_title' => $item['title'],
            'user_name' => $username,
            'categories' => $categories,
            'is_auth' => $is_auth,
            'home_page' => false
        ]);

    } else {
        http_response_code(404);

        $content = include_template('404.php', [
            'categories' => $categories
        ]);

        $layout_content = include_template('layout.php', [
            'page_content' => $content,
            'categories' => $categories,
            'user_name' => $username,
            'meta_title' => 'Товар не найден 404 ошибка',
            'is_auth' => $is_auth,
            'home_page' => false
        ]);
    }


} else {
    http_response_code(404);

    $content = include_template('404.php', [
        'categories' => $categories
    ]);

    $layout_content = include_template('layout.php', [
        'page_content' => $content,
        'categories' => $categories,
        'user_name' => $username,
        'meta_title' => 'Товар не найден 404 ошибка',
        'is_auth' => $is_auth,
        'home_page' => false
    ]);
}

print($layout_content);
