<?php
require_once('../db/conn.php');

$mysqli->execute_query(
  'UPDATE comments SET content = ? WHERE id = ?',
  [$_POST['content'], $_POST['commentId']]
);

header('Location: ../index.php');
exit;
