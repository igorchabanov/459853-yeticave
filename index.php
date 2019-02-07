<?php
    require_once('functions.php');
    require_once('data.php');


    $main_page = include_template('index.php', [
        'adverts'    => $adverts
    ]);

    $layout_content = include_template('layout.php', [
        'page_content' => $main_page,
        'meta_title' => 'Главная',
        'user_name' => 'Игорь',
        'categories' => $categories,
    ]);

    print($layout_content);
