<?php

require_once('mysql_helper.php');

/**
 * Вывод шаблона
 *
 * @param string $name template
 * @param array $data with data
 *
 * @return string
 */
function include_template($name, $data)
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Форматирование цены
 *
 * Приводит цену к виду xx xxx руб.
 *
 * @param int $arg
 * @return string
 */
function format_price($arg)
{
    $price = ceil($arg);
    $result = number_format($price, 0, '', ' ');
    $result .= ' <b class="rub">р</b>';

    return $result;
}

/**
 * Расчет времени до конца ставки
 *
 * @param string $end_date
 * @return string $format
 */
function lot_time_end(string $end_date)
{
    $current_date = date_create('now');
    $new_day = date_create($end_date);

    $diff = date_diff($current_date, $new_day);

    $format = date_interval_format($diff, '%H:%I');

    return $format;
}

/**
 * Подключение к БД
 *
 * @param array $database
 * @return object $db_con
 */

function get_connect(array $database)
{
    $db_con = mysqli_connect($database['host'], $database['user'], $database['passwd'], $database['db_name']);

    if (!$db_con) {
        die ("Ошибка подключения: " . mysqli_connect_error());
    } else {
        mysqli_set_charset($db_con, 'utf8');
    }

    return $db_con;
}

/**
 * Получение категорий из БД
 *
 * @param object $db_con -- обьект подключения
 * @return array $result
 */

function get_categories($db_con)
{
    $sql = 'SELECT `id`, `title` FROM category';
    $query = mysqli_query($db_con, $sql);

    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

/**
 * Получение объявлений из БД
 *
 * @param object $db_con -- обьект подключения
 * @return array $result
 */
function get_adverts($db_con)
{
    $sql = 'SELECT l.id, l.title, l.start_price, l.img_path, l.end_date, c.title AS category
            FROM lot l
            JOIN category c ON l.cat_id = c.id
            WHERE l.end_date > NOW()
            ORDER BY l.created DESC';

    $query = mysqli_query($db_con, $sql);

    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

/**
 * Получает лот по его id
 *
 * @param object $db_con
 * @param int $id
 * @return array $result
 */
function get_item_by_id($db_con, int $id)
{
    $sql = "SELECT l.id, l.title, l.description,  l.img_path, l.rate_step, l.author_id, l.end_date, c.title AS cat,
                (SELECT  COALESCE( MAX(r.amount), l.start_price )
                FROM lot l
                JOIN rate r ON r.lot_id = l.id
                WHERE l.id = '$id') AS price
                FROM lot l
                LEFT JOIN category c ON l.cat_id = c.id
                WHERE l.id = '$id'";


    $query = mysqli_query($db_con, $sql);

    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return mysqli_fetch_assoc($query);;
}


/**
 * Получает товары в определенной категории
 *
 * @param $db_con
 * @param int $id
 * @param int $limit
 * @param int $offset
 * @return array|null
 */

function get_category_items($db_con, int $id, int $limit, int $offset)
{

    $id = mysqli_real_escape_string($db_con, $id);

    $sql = "SELECT l.id, l.title, l.description, l.img_path, l.end_date, c.title AS cat_name,
              (SELECT COALESCE( MAX(r.amount), lot.start_price)
              FROM lot
              LEFT JOIN rate r ON lot.id = r.lot_id
              WHERE lot.id = l.id
              ) AS price
            FROM lot l
            JOIN category c ON l.cat_id = c.id
            WHERE l.cat_id = $id
            ORDER BY l.created DESC LIMIT $limit
            OFFSET $offset";

    $query = mysqli_query($db_con, $sql);

    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

/**
 * Получает кол-во товаров в категории по id категории
 *
 * @param $db_con
 * @param int $id
 * @return int
 */
function get_count_category_items($db_con, int $id)
{
    $id = mysqli_real_escape_string($db_con, $id);

    $sql = "SELECT COUNT(*) AS total 
            FROM lot l
            JOIN category c ON l.cat_id = c.id 
            WHERE l.cat_id = $id";

    $query = mysqli_query($db_con, $sql);

    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    $result = mysqli_fetch_assoc($query)['total'];

    return (int)$result;
}

/**
 * Запись нового лота в БД
 *
 * @param object $db_con -- ресурс соединения
 * @param array $new_lot -- массив с новым товаром
 */
function insert_lot($db_con, $new_lot)
{
    $sql = "INSERT INTO lot(title, description, cat_id, start_price, img_path, rate_step, author_id, winner_id, end_date) 
            VALUES(?, ?, ?, ?, ?, ?, ?, 2, ?);";

    $stmt = db_get_prepare_stmt($db_con, $sql, [
        $new_lot['lot-name'],
        $new_lot['message'],
        $new_lot['category'],
        $new_lot['lot-rate'],
        $new_lot['img_path'],
        $new_lot['lot-step'],
        $new_lot['author'],
        $new_lot['lot-date']
    ]);

    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }
}

/**
 * Проверяет, что переданная дата соответствует формату ДД.ММ.ГГГГ
 * @param string $date строка с датой
 * @return bool
 */
function check_date_format($date)
{
    $result = false;
    $regexp = '/(\d{2})\.(\d{2})\.(\d{4})/m';
    if (preg_match($regexp, $date, $parts) && count($parts) == 4) {
        $result = checkdate($parts[2], $parts[1], $parts[3]);
    }
    return $result;
}

/**
 *  Проверяет на существование пользователя в БД по email
 *
 * @param object $db_con
 * @param string $email
 * @return bool
 */
function check_user_email($db_con, string $email)
{

    $email = mysqli_real_escape_string($db_con, $email);
    $sql = "SELECT email FROM user WHERE email = '$email'";
    $query = mysqli_query($db_con, $sql);

    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            return true;
        }

        return false;
    } else {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }
}

/**
 * Записывает нового пользователя
 *
 * @param $db_con
 * @param array $new_user
 */

function insert_new_user($db_con, array $new_user)
{
    $password = password_hash($new_user['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO user(name, email, passwd, contact, img) VALUES(?, ?, ?, ?, ?)";

    $stmt = db_get_prepare_stmt($db_con, $sql, [
        $new_user['name'],
        $new_user['email'],
        $password,
        $new_user['message'],
        $new_user['avatar']
    ]);

    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }
}

/**
 * Получает данные пользователя
 *
 * @param object $db_con
 * @param string $email
 * @return array|null
 */

function get_user($db_con, string $email)
{

    $email = mysqli_real_escape_string($db_con, $email);

    $sql = "SELECT * FROM user WHERE email = '$email'";
    $query = mysqli_query($db_con, $sql);


    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return mysqli_fetch_assoc($query);;
}

/**
 * Получает ставки лота по id
 *
 * @param $db_con
 * @param int $lot_id
 *
 * @return array $result
 */

function get_lot_rates($db_con, int $lot_id)
{
    $sql = "SELECT r.id, r.created, r.amount, r.user_id, u.name
            FROM rate r
            JOIN user u
            WHERE lot_id = '$lot_id' && r.user_id = u.id
            ORDER BY created DESC LIMIT 10";

    $query = mysqli_query($db_con, $sql);

    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return mysqli_fetch_all($query, MYSQLI_ASSOC);;
}

/**
 * Добавляет запись в ставки
 *
 * @param $db_con
 * @param $user
 * @param $rate
 * @param $lot_id
 */

function insert_new_rate($db_con, $user, $rate, $lot_id)
{
    $user = intval($user);
    $rate = intval($rate);
    $lot_id = intval($lot_id);

    $sql = "INSERT INTO rate(amount, user_id, lot_id) VALUES(?, ?, ?)";

    $stmt = db_get_prepare_stmt($db_con, $sql, [
        $rate,
        $user,
        $lot_id
    ]);

    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }
}


/**
 * Приводит дату к читаемому виду в истории ставок
 *
 * @param $date
 * @return string
 */
function history_time($date)
{
    $arr_hours = [
        'час',
        'часа',
        'часов'
    ];

    $arr_minutes = [
        'минуту',
        'минуты',
        'минут'
    ];

    $time = time() - strtotime($date);

    $result = date('d.m.Y в H:i', strtotime($date));

    $hours = (int)floor(($time % 86400) / 3600);
    $minutes = (int)floor(($time % 3600) / 60);
    $days = (int)floor(($time / 86400));

    if ($days === 0) {
        if ($hours === 0 && $minutes === 0) {
            $result = $minutes < 1 ? 'Меньше минуты' : '';
        } else {
            if ($hours === 0) {
                $result = $minutes >= 1 ? $minutes . ' ' . get_num_ending($minutes, $arr_minutes) : '';
            } else {
                $result = $hours > 1 ? $hours . ' ' . get_num_ending($hours, $arr_hours) : '';
            }
        }
        $result .= ' назад';
    }

    return $result;
}

/**
 * Приводит склонения к правильному виду в истории ставок
 *
 * @param int $number
 * @param array $ending_array
 * @return mixed
 */
function get_num_ending(int $number, array $ending_array)
{
    $number = $number % 100;
    if ($number >= 11 && $number <= 19) {
        $ending = $ending_array[2];
    } else {
        $number = $number % 10;
        switch ($number) {
            case(1):
                $ending = $ending_array[0];
                break;
            case(2):
            case(3):
            case(4):
                $ending = $ending_array[1];
                break;
            default:
                $ending = $ending_array[2];
        }
    }

    return $ending;
}

/**
 * Получает кол-во записей по поиску
 *
 * @param $db_con
 * @param string $phrase
 * @return int $result
 */
function get_count_items($db_con, string $phrase)
{
    $phrase = mysqli_real_escape_string($db_con, $phrase);

    $sql = "SELECT COUNT(*) AS total FROM lot WHERE MATCH (title, description) AGAINST ('$phrase' IN BOOLEAN MODE)";

    $query = mysqli_query($db_con, $sql);

    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    $result = mysqli_fetch_assoc($query)['total'];

    return (int)$result;
}

/**
 * Получает искомые лоты
 *
 * @param $db_con
 * @param string $phrase
 * @param int $limit
 * @param int $offset
 * @return array|null
 */
function get_search_result($db_con, string $phrase, int $limit, int $offset)
{

    $phrase = mysqli_real_escape_string($db_con, $phrase);

    $sql = "SELECT l.id, l.title, l.description, l.img_path, l.end_date, c.title AS cat_name,
              (SELECT COALESCE( MAX(r.amount), lot.start_price)
              FROM lot
              LEFT JOIN rate r ON lot.id = r.lot_id
              WHERE lot.id = l.id
              ) AS price
            FROM lot l
            JOIN category c ON l.cat_id = c.id
            WHERE MATCH (l.title, l.description) AGAINST ('$phrase' IN BOOLEAN MODE)
            ORDER BY l.created DESC LIMIT $limit
            OFFSET $offset";

    $query = mysqli_query($db_con, $sql);

    if (!$query) {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return mysqli_fetch_all($query, MYSQLI_ASSOC);
}

/**
 * Определяет mime тип изображения
 *
 * @param $image
 * @return string $result
 */

function get_image_extension($image)
{
    $file_mime = mime_content_type($image);
    $result = '';
    if ($file_mime === "image/jpeg" || $file_mime === "image/jpg") {
        $result = '.jpg';
    } elseif ($file_mime === "image/png") {
        $result = '.png';
    }

    return $result;
}
