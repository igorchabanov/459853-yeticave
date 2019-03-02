<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = $_POST;

    $required = [
        'email',
        'password',
        'name',
        'message'
    ];

    $dict = [
        'email' => 'Введите e-mail',
        'password' => 'Введите пароль',
        'name' => 'Введите имя',
        'message' => 'Напишите как с вами связаться'
    ];

    $errors = [];

    foreach ($required as $field) {
        if (empty($_POST[$field]) || $_POST[$field] === '') {
            $errors[$field] = $dict[$field];
        }
    }

    // Email
    if(!empty($_POST['email']) && !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) ) {
        $errors['email'] = 'Некорректный email';
    }

    // uniq email
    if (check_user_email($db_con, $_POST['email'])) {
        $errors['email'] = 'Пользователь с таким email существует';
    }

    // todo Добавить обработку изображения

    if(count($errors)) {
        $page_content = include_template('registration.php', [
            'errors' => $errors,
            'categories' => $categories,
            'new_user' => $new_user
        ]);
    } else {
        $added = insert_new_user($db_con, $new_user);

        if ($added) {
            header("Location: /login.php");
            exit();
        }
    }

    var_dump($errors);
} else {
    $page_content = include_template('registration.php', [
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'page_content' => $page_content,
    'meta_title' => 'Регистрация аккаунта',
    'user_name' => $username,
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);

