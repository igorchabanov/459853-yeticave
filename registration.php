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
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    // uniq email
    if (!empty($_POST['email']) && check_user_email($db_con, $_POST['email'])) {
        $errors['email'] = 'Пользователь с таким email существует';
    }

    if (isset($_FILES['avatar']) && !$_FILES['avatar']['error']) {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $file_mime = mime_content_type($tmp_name);


        if ($file_mime !== "image/png" && $file_mime !== 'image/jpeg') {
            $errors['avatar'] = 'Неверный тип изображения';
        }

        if ($file_mime === "image/jpeg") {
            $img_ext = '.jpg';
        } elseif ($file_mime === "image/png") {
            $img_ext = '.png';
        }
    }

    if (count($errors)) {
        $page_content = include_template('registration.php', [
            'errors' => $errors,
            'categories' => $categories,
            'new_user' => $new_user
        ]);
    } else {

        if (isset($_FILES['avatar']) && !$_FILES['avatar']['error']) {
            if (!is_dir($uploads)) {
                mkdir($uploads, 0777, true);
            }

            $new_filename = uniqid('user_') . $img_ext;
            $new_user['avatar'] = $uploads . $new_filename;

            move_uploaded_file($tmp_name, $uploads . $new_filename);
        } else {
            $new_user['avatar'] = '';
        }

        $added = insert_new_user($db_con, $new_user);

        if ($added) {
            header("Location: /login.php");
            exit();
        }
    }
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

