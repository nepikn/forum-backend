<?php
require_once('../db/conn.php');

$mysqli->query(sprintf(
  // soft delete
  'UPDATE comments SET is_deleted = TRUE WHERE id = %s',
  // 'DELETE FROM comments WHERE id = %s',
  $_POST['commentId']
));

header('Location: ../index.php');
exit;
