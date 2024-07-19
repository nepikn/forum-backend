<?php
require_once '../controllers/comment.php';
require_once '../util/router.php';

$router = new Router('comment', new CommentController());

$router->post('');
$router->get('');
// $router->get('/:id');
$router->get('/length');
$router->put('/:id');
$router->delete('/:id');
