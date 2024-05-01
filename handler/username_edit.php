<?php
require_once('../db/conn.php');
session_start();

$new_username = $_POST['name'];

$mysqli->execute_query(
  'UPDATE users SET name = ? WHERE id = ?',
  [
    $new_username,
    $_SESSION['user']['id']
  ]
);

header('Location: ../index.php');
exit;
