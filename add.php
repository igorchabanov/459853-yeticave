<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;

    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
    $img_types = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg'
    ];

    foreach ($required as $field) {
        if(empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

//    var_dump($new_lot);
    // Загрузка img
    if(isset($_FILES['lot-img']) && !empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_name = $_FILES['lot-img']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if($file_type !== "image/jpeg") {
            $errors['file'] = 'Загрузите изображение';
        } else {

            if (!is_dir($uploads)) {
                mkdir($uploads, 0777, true);
            }
            move_uploaded_file($tmp_name, $uploads . $file_name);
            $new_lot['img_path'] = $file_name;

            var_dump(pathinfo($file_name, PATHINFO_EXTENSION));
        }
    }

    if(count($errors)) {
        var_dump($errors);
        $add_page = include_template('add.php', [
            'errors' => $errors,
            'categories' => $categories,
        ]);
    }
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
