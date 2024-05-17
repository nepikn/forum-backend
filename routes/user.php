<?php
require_once '../controllers/user.php';
require_once '../util/router.php';

$user = new ClientUserController();
$router = new Router('/api/user');

// var_export(getallheaders());
$router->put('/name', [$user, 'putName']);
// $router->get('', fn () => get_object_vars($user));
$router->get('', [$user, 'getProps']);
$router->get('/name', [$user, 'getName']);
