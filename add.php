<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit();
}

$categories = get_categories($db_con);

$new_lot = [];
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;

    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $dict = [
        'lot-name' => 'Введите наименование лота',
        'category' => 'Выберите категорию',
        'message' => 'Напишите описание лота',
        'lot-rate' => 'Введите начальную цену',
        'lot-step' => 'Введите шаг ставки',
        'lot-date' => 'Введите дату завершения торгов',
        'file' => 'Загрузите изображение'
    ];

    $numbers = ['lot-rate', 'lot-step'];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = $dict[$field];
        }
    }

    foreach ($numbers as $field) {
        if (empty($_POST[$field]) || !is_numeric($_POST[$field]) || $_POST[$field] <= 0) {
            $errors[$field] = $dict[$field];
        }
    }

    if (!empty($_POST['lot-date'])) {
        if (!check_date_format($_POST['lot-date'])) {
            $errors['lot-date'] = 'Некорректный формат даты';
        } elseif (strtotime($_POST['lot-date']) < strtotime('tomorrow')) {
            $errors['lot-date'] = 'Дата окончания должна быть позже на 1 день';
        }
    }


    if (!empty($_POST['category'])) {
        if (!check_cat_exist($db_con, $_POST['category'])) {
            $errors['category'] = 'Такой категории не существует';
        }
    }
    
    if (isset($_FILES['lot-img']) && !$_FILES['lot-img']['error']) {
        $file_info = get_file_info($_FILES['lot-img'], $errors);
    } else {
        $errors['file'] = $dict['file'];
    }

    if (!count($errors)) {

        $new_lot['img_path'] = user_upload_image($uploads, $file_info);

        $new_lot['author'] = $_SESSION['user']['id'];

        //Insert
        insert_lot($db_con, $new_lot);

        $lot_id = mysqli_insert_id($db_con);

        header("Location: /lot.php?id=" . $lot_id);
        die();
    }
}

$add_page = include_template('add.php', [
    'categories' => $categories,
    'errors' => $errors,
    'new_lot' => $new_lot
]);

$layout_content = include_template('layout.php', [
    'page_content' => $add_page,
    'meta_title' => 'Добавление лота',
    'user_name' => $username,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);
