<?php
session_start();
require_once('db.php');

$name_checking = empty($_POST['passwd']);
if ($name_checking) {
  $_SESSION['user'] = ['name' => $_POST['name']];
} else {
  // $mysqli->query('DELETE FROM users');
  $mysqli->execute_query('INSERT INTO users VALUES (0, ?, ?)', [$_POST['name'], password_hash($_POST['name'], PASSWORD_DEFAULT)]);
  $_SESSION['user']['id'] = $mysqli->query('SELECT LAST_INSERT_ID()')->fetch_column(0);
}

header(sprintf('Location: %s.php', $name_checking ? 'auth' : 'index'));
exit;
