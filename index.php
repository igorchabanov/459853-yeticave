<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

date_default_timezone_set('Europe/Moscow');


$categories = get_categories($db_con);
$adverts = get_adverts($db_con);

$main_page = include_template('index.php', [
    'adverts' => $adverts,
    'categories' => $categories,
]);

$layout_content = include_template('layout.php', [
    'page_content' => $main_page,
    'meta_title' => 'Главная',
    'user_name' => $username,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'home_page' => true
]);

print($layout_content);
