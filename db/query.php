<?php
require_once '../db/conn.php';

function query($sql, ...$values) {
  global $mysqli;
  return $mysqli->query(count($values) ? sprintf($sql, ...$values) : $sql);
}

function dbGet($user_id, ...$cols) {
  $col = join(', ', $cols);
  $sql = "SELECT $col FROM users WHERE id = $user_id";

  return $GLOBALS['mysqli']
    ->query($sql)
    ->fetch_assoc();
}

function dbSet($user_id, $col, $value) {
  // todo
  return $value;
}

function dbDelete($user_id) {
  // todo
}
