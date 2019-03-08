<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);
$errors = [];
$item_id = $_GET['id'] ? intval($_GET['id']) : '';
$item = [];
$rates = [];
$user_id = '';
$exsist_rate = false;
$author_lot = false;

// User id
if (!empty($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
}

// Item id
if (!empty($item_id)) {
    $item = get_item_by_id($db_con, $item_id);
    $rates = get_lot_rates($db_con, $item_id);
    $item['next_rate'] = $item['price'] + $item['rate_step'];
}

if (!empty($rates)) {
    foreach ($rates as $rate) {
        if ($rate['user_id'] === $user_id) {
            $exsist_rate = true;
        }
    }
}

if ($item['author_id'] === $user_id) {
    $author_lot = true;
}

if ($is_auth) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['cost'])) {

            if ($_POST['cost'] >= $item['next_rate']) {
                insert_new_rate($db_con, $_SESSION['user']['id'], $_POST['cost'], $item['id']);

                header("Location: /lot.php?id=" . $item_id);
                exit();
            } else {
                $errors['cost'] = 'Сумма должна быть больше';
            }

        } else {
            $errors['cost'] = 'Введите Вашу ставку';
        }
    }
}

if (!empty($item) || !empty($item_id)) {
    $content = include_template('lot.php', [
        'errors' => $errors,
        'categories' => $categories,
        'lot' => $item,
        'rates' => $rates,
        'is_auth' => $is_auth,
        'exsist_rate' => $exsist_rate,
        'author_lot' => $author_lot
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
