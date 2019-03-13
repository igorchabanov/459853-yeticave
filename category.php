<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);

$category_id = isset($_GET['id']) ? intval($_GET['id']) : '';
$page_items = 9;
$offset = 0;
$current_page = intval($_GET['page'] ?? 1);
$link = "category.php?id={$category_id}";

if (!empty($category_id)) {
    $items = get_category_items($db_con, $category_id, $page_items, $offset);
}

if (empty($category_id) || empty($items)) {
    http_response_code(404);

    $content = include_template('404.php', [
        'categories' => $categories
    ]);
} else {
    $pages = [];

    $items_count = get_count_category_items($db_con, $category_id);
    $pages_count = (int)ceil($items_count / $page_items);
    $offset = ($current_page - 1) * $page_items;

    if ($pages_count > 1) {
        $pages = range(1, $pages_count);
    }

    $pagination = include_template('pagination.php', [
        'pages' => $pages,
        'cur_page' => $current_page,
        'link' => $link,
    ]);

    $content = include_template('category.php', [
        'items' => $items,
        'cat_name' => $items[0]['cat_name'] ?? '',
        'pagination' => $pagination,
        'pages' => $pages,
    ]);

}
$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'categories' => $categories,
    'user_name' => $username,
    'meta_title' => isset($item['title']) ? $item['title'] : 'В категории нет товаров - 404 ошибка',
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);
