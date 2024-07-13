<?php
require_once '../controllers/session.php';
require_once '../util/router.php';

$router = new Router('session', new SessionController());

$router->post('');
$router->get('/:prop');
$router->put('');
$router->delete('');
