<?php
session_start();

$is_auth = isset($_SESSION['user']);
$username['name'] = isset($_SESSION['user']) ? $_SESSION['user']['name'] : '';
$username['avatar'] = isset($_SESSION['user']) ? $_SESSION['user']['img'] : '';
$user_id = isset($_SESSION['user']) ? $_SESSION['user']['id'] : '';
$uploads = 'uploads/';

$db_con = get_connect($database);
