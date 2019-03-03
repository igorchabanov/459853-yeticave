<?php
session_start();

$is_auth = isset($_SESSION['user']) ?? false;
$username = isset($_SESSION['user']) ? $_SESSION['user']['name'] : '';
$uploads = 'uploads/';

$db_con = get_connect($database);
