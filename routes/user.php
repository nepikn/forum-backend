<?php
require_once '../controllers/user.php';
require_once '../util/router.php';

$router = new Router('user', new UserController());

$router->post('');
$router->get('');
$router->get('/:prop');
$router->put('');
$router->delete('');

// $router->post('', $user_controller);
// $router->get('', );
// $router->get('/:prop', $user_controller);
// $router->put('/:prop', $user_controller);
// $router->delete('', $user_controller);
