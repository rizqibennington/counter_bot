<?php
$config = parse_ini_file("./config.ini");
$server = $config['server'];
$username = $config['username'];
$password = $config['password'];
$db = $config['db'];
$token = $config['token'];

$conn = mysqli_connect($server, $username, $password, $db);
include 'query.php';
?>