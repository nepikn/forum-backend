<?php
require_once '../controllers/user.php';
require_once '../util/router.php';

$user = new UserController();
$router = new Router('/api/user');

$router->get('/name', [$user, 'getName']);

// var_export($user);
