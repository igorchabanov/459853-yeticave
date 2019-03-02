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
        if (empty($_POST[$field]) || !is_numeric($_POST[$field]) || $_POST[$field] <= 0) {
            $errors[$field] = $dict[$field];
        }
    }

    if (empty($_POST['lot-date']) || !check_date_format($_POST['lot-date'])) {
        $errors['lot-date'] = 'Некорректный формат даты';
    } elseif (strtotime($_POST['lot-date']) < strtotime('tomorrow')) {
        $errors['lot-date'] = 'Дата окончания должна быть позже на 1 день';
    }


    // Загрузка img
    if (isset($_FILES['lot-img']) && !$_FILES['lot-img']['error']) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_mime = mime_content_type($tmp_name);


        if ($file_mime !== "image/png" && $file_mime !== 'image/jpeg') {
            $errors['file'] = $dict['file'];
        }

        if ($file_mime === "image/jpeg") {
            $img_ext = '.jpg';
        } elseif ($file_mime === "image/png") {
            $img_ext = '.png';
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

        $new_filename = uniqid('image_') . $img_ext;
        $new_lot['img_path'] = $uploads . $new_filename;

        move_uploaded_file($tmp_name, $uploads . $new_filename);

        //Insert
        $added = insert_lot($db_con, $new_lot);

        if ($added) {
            $lot_id = mysqli_insert_id($db_con);

            header("Location: /lot.php?id=" . $lot_id);
            die();
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
