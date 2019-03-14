<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);
$new_user = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = $_POST;

    $required = [
        'email',
        'password',
        'name',
        'message',
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
    if (!empty($_POST['email'])) {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный email';
        } elseif (check_user_email($db_con, $_POST['email'])) {
            $errors['email'] = 'Пользователь с таким email существует';
        }
    }

    if (isset($_FILES['image']) && !$_FILES['image']['error']) {
        $file_info = get_file_info($_FILES['image'], $errors);
    }

    if (!count($errors)) {
        if (isset($_FILES['image']) && !$_FILES['image']['error']) {
            $new_user['file'] = user_upload_image($uploads, $file_info);
        } else {
            $new_user['file'] = '';
        }

        insert_new_user($db_con, $new_user);

        header("Location: /login.php");
        exit();
    }
}

$page_content = include_template('registration.php', [
    'errors' => $errors,
    'categories' => $categories,
    'new_user' => $new_user
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'page_content' => $page_content,
    'meta_title' => 'Регистрация аккаунта',
    'user_name' => $username,
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);

