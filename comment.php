<?php
require_once('./db.php');
session_start();

$user_id = $_SESSION['user']['id'];
$content = $_POST['content'];
// echo var_export($user_id) . '<br/>';
// echo var_export($comment) . '<br/>';
$mysqli->execute_query('INSERT INTO comments VALUES (0, ?, ?)', [$user_id, $content]);

header('Location: index.php');
exit;
