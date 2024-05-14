<?php
require_once '../db/conn.php';
require_once '../controllers/user.php';
require_once '../util/router.php';
session_start();

$user = ($_SESSION['user'] ??= ['id' => null, 'err' => false]);
$user_id = $user['id'];
$user_controller = new UserController($user_id);
$router = new Router('/api/user');

// $router->get('/name', );
$router->get('/name', [$user_controller, 'getName']);
