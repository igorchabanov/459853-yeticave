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
 * @param int $arg
 *
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
 * Форматирование цены 2 способ
 *
 * @param int $argument
 *
 * @return string
 */

function format_price_string($argument)
{
    $price = (string)ceil($argument);
    $result = $price;

    if ($price >= 1000) {
        $last_numbers = substr($price, -3);
        $count_diff = strlen($price) - strlen($last_numbers);
        $first_numbers = substr($price, 0, $count_diff);

        $result = $first_numbers . ' ' . $last_numbers;
    }

    $result .= ' <b class="rub">р</b>';
    return $result;
}

/**
 * Расчет времени до конца ставки
 *
 * @return string
 */
function lot_time_end()
{
    $current_date = date_create('now');
    $new_day = date_create('tomorrow 00:00:00');

    $diff = date_diff($current_date, $new_day);

    $format = date_interval_format($diff, '%H:%I');

    return $format;
}

/**
 * Подключение к БД
 *
 * @param array $database
 *
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
 *
 * @return array $result
 */

function get_categories($db_con)
{
    $sql = 'SELECT `id`, `title` FROM category';
    $query = mysqli_query($db_con, $sql);

    if ($query) {
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    } else {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return $result;
}

/**
 * Получение объявлений из БД
 *
 * @param object $db_con -- обьект подключения
 *
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

    if ($query) {
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    } else {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return $result;
}

/**
 * Получаем лот по его id
 *
 * @param object $db_con
 * @param int $id
 *
 * @return array $result
 */

function get_item_by_id($db_con, int $id)
{
    $sql = "SELECT l.id, l.title, l.description,  l.img_path, l.rate_step, l.author_id, c.title AS cat,
                (SELECT  COALESCE( MAX(r.amount), l.start_price )
                FROM lot l
                JOIN rate r ON r.lot_id = l.id
                WHERE l.id = '$id') AS price
                FROM lot l
                LEFT JOIN category c ON l.cat_id = c.id
                WHERE l.id = '$id'";


    $query = mysqli_query($db_con, $sql);

    if ($query) {
        $result = mysqli_fetch_assoc($query);
    } else {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return $result;
}

/**
 * Запись лота в БД
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
 *
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
        } else {
            return false;
        }
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


    if ($query) {
        $result = mysqli_fetch_assoc($query);
    } else {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return $result;
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

    if ($query) {
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    } else {
        die('Произошла ошибка ' . mysqli_error($db_con));
    }

    return $result;
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
 * Приводит дату к читаемому виду в истории
 *
 * @param $date
 * @return false|string
 */
function history_time($date)
{
    $time = time() - strtotime($date);

    if ($time > 60 && $time < 3600) {
        $ago = floor(($time % 3600) / 60) . ' минут назад';
    } elseif ($time > 3600 && $time < 86400) {
        $ago = floor($time / 3600) . ' часов назад';
    } elseif ($time > 86400) {
        $ago = date('d.m.Y в H:i', strtotime($date));
    } else {
        $ago = 'меньше минуты назад';
    }
    return $ago;
}
