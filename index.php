<?php
require_once('functions.php');
require_once('data.php');

date_default_timezone_set('Europe/Moscow');

$is_auth = rand(0, 1);

$main_page = include_template('index.php', [
    'adverts' => $adverts,
    'categories' => $categories,
]);

$layout_content = include_template('layout.php', [
    'page_content' => $main_page,
    'meta_title' => 'Главная',
    'user_name' => 'Игорь',
    'categories' => $categories,
    'is_auth' => $is_auth
]);




print($layout_content);
