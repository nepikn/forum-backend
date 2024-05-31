<?php
require_once '../controllers/user.php';
require_once '../util/router.php';

$user_controller = new UserController();
$router = new Router('/api/user', $user_controller);

$router->post('');
$router->get('');
$router->get('/:prop');
$router->put('');
$router->put('/:prop');
$router->delete('');
$router->delete('/session');

// $router->post('', $user_controller);
// $router->get('', );
// $router->get('/:prop', $user_controller);
// $router->put('/:prop', $user_controller);
// $router->delete('', $user_controller);
