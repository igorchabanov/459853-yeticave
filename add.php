<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);

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

    $errors = [];

    foreach ($required as $field) {
        if (empty($_POST[$field]) || $_POST[$field] === '') {
            $errors[$field] = $dict[$field];
        }
    }

    foreach ($numbers as $field) {
        if (!is_numeric($_POST[$field]) || $_POST[$field] <= 0) {
            $errors[$field] = $dict[$field];
        }
    }


    $user_date = date('d.m.Y', strtotime($_POST['lot-date']));

    if (!check_date_format($user_date)) {
        $errors['lot-date'] = 'Некорректный формат даты';
    } elseif (!check_date_end($_POST['lot-date'])) {
        $errors['lot-date'] = 'Дата окончания должна быть позже на 1 день';
    }

    // Загрузка img
    if (isset($_FILES['lot-img']) && !empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_name = $_FILES['lot-img']['name'];
        $file_type = mime_content_type($tmp_name);

        if ($file_type === "image/jpeg") {
            $img_ext = '.jpg';
        } elseif ($file_type === "image/png") {
            $img_ext = '.png';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if (empty($finfo) || $file_type !== "image/jpeg") {
            $errors['file'] = $dict['file'];
        }
    } else {
        $errors['file'] = $dict['file'];
    }


    if (count($errors)) {
        $add_page = include_template('add.php', [
            'errors' => $errors,
            'categories' => $categories,
            'new_lot' => $new_lot
        ]);
    } else {
        if (!is_dir($uploads)) {
            mkdir($uploads, 0777, true);
        }

        $new_filename = uniqid('image_') . '.' . $img_ext;
        $new_lot['img_path'] = $uploads . $new_filename;

        move_uploaded_file($tmp_name, $uploads . $new_filename);

        //Insert
        $added = insert_lot($db_con, $new_lot);

        if ($added) {
            $lot_id = mysqli_insert_id($db_con);

            header("Location: /lot.php?id=" . $lot_id);
            die();
        } else {
            $add_page = include_template('error.php', [
                'message' => 'Произошла ошибка, попробуйте отправить форму позже.',
                'categories' => $categories
            ]);
        }
    }


} else {
    // Empty page
    $add_page = include_template('add.php', [
        'categories' => $categories,
    ]);
}

$layout_content = include_template('layout.php', [
    'page_content' => $add_page,
    'meta_title' => 'Добавление лота',
    'user_name' => $username,
    'categories' => $categories,
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);
