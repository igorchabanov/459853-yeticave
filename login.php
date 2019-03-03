<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);

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

    $errors = [];

    foreach ($required as $field) {
        if (empty($_POST[$field]) || $_POST[$field] === '') {
            $errors[$field] = $dict[$field];
        }
    }

    // check email
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    } elseif (!empty($_POST['email']) && !check_user_email($db_con, $_POST['email'])) {
        $errors['email'] = 'Пользователь с таким email не существует';
    }

    // user exsist
    if (!empty($_POST['email']) && !empty($_POST['password']) && check_user_email($db_con, $_POST['email'])) {
        $user_db = get_user($db_con, $_POST['email']);

        if (password_verify($_POST['password'], $user_db['passwd'])) {
            $_SESSION['user'] = $user_db;
            header('Location: /');
            exit();

        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    }

    if (count($errors)) {
        $page_content = include_template('login.php', [
            'errors' => $errors,
            'categories' => $categories,
            'user' => $user
        ]);
    } else {
        $page_content = include_template('login.php', [
            'categories' => $categories
        ]);
    }

} else {
    $page_content = include_template('login.php', [
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'page_content' => $page_content,
    'meta_title' => 'Войти в аккаунт',
    'user_name' => $username,
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);
