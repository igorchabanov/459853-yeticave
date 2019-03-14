<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit();
}

$page_title = "Мои ставки";


$categories = get_categories($db_con);

$items = get_items_rates($db_con, $user_id);

$page_content = include_template('mybets.php', [
    'page_title' => $page_title,
    'categories' => $categories,
    'items' => $items,
    'user_id' => $user_id,
]);

$layout_content = include_template('layout.php', [
    'page_content' => $page_content,
    'meta_title' => $page_title,
    'user_name' => $username,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);
