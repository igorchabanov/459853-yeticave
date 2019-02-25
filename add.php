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
    $errors = [];

    foreach ($required as $field) {
        if (empty($_POST[$field]) || $_POST[$field] === 'default') {
            $errors[$field] = $dict[$field];
        }
    }

    // Загрузка img
    if (isset($_FILES['lot-img']) && !empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_name = $_FILES['lot-img']['name'];
        $img_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if (empty($finfo) || $file_type !== "image/jpeg") {
            $errors['file'] = $dict['file'];
        } else {

            if (!is_dir($uploads)) {
                mkdir($uploads, 0777, true);
            }

            $new_filename = uniqid('image_') . '.' . $img_ext;
            $new_lot['img_path'] = $uploads . $new_filename;

            move_uploaded_file($tmp_name, $uploads . $new_filename);

            $added = insert_lot($db_con, $new_lot);

            // Insert
            if ($added) {
                $lot_id = mysqli_insert_id($db_con);

                header("Location: /lot.php?id=" . $lot_id);
            }
        }
    }

    if (count($errors)) {
        var_dump($errors);
        $add_page = include_template('add.php', [
            'errors' => $errors,
            'categories' => $categories,
            'new_lot' => $new_lot
        ]);
    } else {
        $add_page = include_template('add.php', [
            'categories' => $categories
        ]);
    }
} else {
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
