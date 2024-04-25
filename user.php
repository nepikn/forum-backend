<?php
require_once('db.php');

// $mysqli->query('DELETE FROM users');
$mysqli->execute_query('INSERT INTO users VALUES (0, ?, ?)', [$_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT)]);

session_start();

$_SESSION['user_id'] = $mysqli->query('SELECT LAST_INSERT_ID()')->fetch_column(0);

header('Location: index.php');
exit;
