<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);

$errors = [];
$user = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST;

    $required = [
        'email',
        'password'
    ];

    $dict = [
        'email' => 'Введите e-mail',
        'password' => 'Введите пароль'
    ];

    $email_exsist = false;

    if (!empty($_POST['email'])) {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            if (check_user_email($db_con, $_POST['email'])) {

                $email_exsist = true;

            } else {
                $errors['email'] = 'Пользователь с таким email не существует';
            }
        } else {
            $errors['email'] = 'Некорректный email';
        }
    } else {
        $errors['email'] = 'Пустое поле';
    }

    if (!empty($_POST['password'])) {
        if ($email_exsist) {

            $user_db = get_user($db_con, $_POST['email']);

            if (password_verify($_POST['password'], $user_db['passwd'])) {
                $_SESSION['user'] = $user_db;
                header('Location: /');
                exit();
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        }
    } else {
        $errors['password'] = 'ПУстой пароль';
    }
}

$page_content = include_template('login.php', [
    'errors' => $errors,
    'categories' => $categories,
    'user' => $user
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
