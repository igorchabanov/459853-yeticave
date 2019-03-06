<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);
$errors = [];
$item_id = $_GET['id'] ? intval($_GET['id']) : '';
$item = [];
$rates = [];

if ($item_id) {
    $item = get_item_by_id($db_con, $item_id);

    $rates = get_lot_rates($db_con, $item_id);

    $item['next_rate'] = $item['price'] + $item['rate_step'];
}

//var_dump($rates);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['cost'])) {

        // todo проверка на то что пользователь еще не делал ставку

        if ($_POST['cost'] >= $item['next_rate']) {
            insert_new_rate($db_con, $_SESSION['user']['id'], $_POST['cost'], $item['id']);

            header("Location: /lot.php?id=" . $lot_id);
            exit();
        } else {
            $errors['cost'] = 'Сумма должна быть больше';
        }

    } else {
        $errors['cost'] = 'Введите Вашу ставку';
    }
}

var_dump($errors);

if ($item) {
    $content = include_template('lot.php', [
        'errors' => $errors,
        'categories' => $categories,
        'lot' => $item,
        'rates' => $rates,
        'is_auth' => $is_auth
    ]);
} else {
    http_response_code(404);

    $content = include_template('404.php', [
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'categories' => $categories,
    'user_name' => $username,
    'meta_title' => isset($item['title']) ? $item['title'] : 'Товар не найден 404 ошибка',
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);
