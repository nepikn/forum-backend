<?php
session_start();
require_once('../db/conn.php');

// $mysqli->query('DELETE FROM users');
$user = &$_SESSION['user'];
$db = &$user['db'];
$id = &$user['id'];
$err = &$user['err'];
$name = $_POST['name'];
$passwd = $_POST['passwd'];

if (empty($passwd)) {
  $user['name'] = htmlspecialchars($name);
  $db = $mysqli->execute_query('SELECT * FROM users WHERE name = ?', [$name])->fetch_assoc();
} else if ($db === NULL) {
  $mysqli->execute_query('INSERT INTO users VALUES (0, ?, ?)', [$name, password_hash($passwd, PASSWORD_DEFAULT)]);
  $id = $mysqli->query('SELECT LAST_INSERT_ID()')->fetch_column();
} else if (password_verify($passwd, $db['password'])) {
  $err = false;
  $id = $db['id'];
} else {
  $err = true;
}

header('Location: ../auth.php');
exit;
