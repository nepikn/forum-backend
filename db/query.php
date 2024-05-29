<?php
require_once '../db/conn.php';

function query($sql, ...$values) {
  global $mysqli;
  return $mysqli->query(count($values) ? sprintf($sql, ...$values) : $sql);
}

function getDb($user_cond, ...$cols) {
  global $mysqli;
  $conds = is_array($user_cond) ? $user_cond : ['id' => $user_cond];
  $col = $cols ? join(', ', $cols) : '*';
  $sql = sprintf("SELECT $col FROM users WHERE %s", join(' AND ', array_map(
    fn ($key) => "$key = ?",
    array_keys($conds)
  )));

  return $mysqli
    ->execute_query($sql, array_values($conds))
    ->fetch_assoc();
}

function setDb($user_id, $col, $value) {
  // todo
  return $value;
}

function dbDelete($user_id) {
  // todo
}
