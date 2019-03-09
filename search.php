<?php
require_once('functions.php');
require_once('config/db.php');
require_once('config/config.php');

$categories = get_categories($db_con);

$search_phrase = $_GET['search'] ? trim(strip_tags($_GET['search'])) : '';

if (empty($search_phrase)) {
    $content = include_template('error.php', [
        'message' => 'Ничего не найдено по вашему запросу',
        'categories' => $categories,
    ]);
} else {
    $current_page = intval($_GET['page'] ?? 1);
    $page_items = 9;
    $pages = [];

    $items_count = get_count_items($db_con, $search_phrase);
    $pages_count = (int)ceil($items_count / $page_items);
    $offset = ($current_page - 1) * $page_items;

    if ($pages_count > 1) {
        $pages = range(1, $pages_count);
    }

    $search_result = get_search_result($db_con, $search_phrase, $page_items, $offset);


    $content = include_template('search.php', [
        'items' => $search_result,
        'search_phrase' => $search_phrase,
        'categories' => $categories,
        'pagination' => $pages,
        'cur_page' => $current_page
    ]);
}

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'categories' => $categories,
    'user_name' => $username,
    'meta_title' => 'Результат поиска',
    'is_auth' => $is_auth,
    'home_page' => false
]);

print($layout_content);
