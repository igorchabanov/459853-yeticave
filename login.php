<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);



$page_content = include_template('login.php', [
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'page_content' => $page_content,
    'meta_title' => 'Войти в аккаунт',
    'user_name' => $username,
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);
