<?php
require_once '../db/conn.php';

function query($sql, ...$values) {
  global $mysqli;
  return $mysqli->query(count($values) ? sprintf($sql, ...$values) : $sql);
}

function getDb($id_or_user_cond, ...$cols) {
  global $mysqli;
  $conds = is_array($id_or_user_cond) ? $id_or_user_cond : ['id' => $id_or_user_cond];
  $col = $cols ? join(', ', $cols) : '*';
  $sql = sprintf("SELECT $col FROM users WHERE %s", join(' AND ', array_map(
    fn ($key) => "$key = ?",
    array_keys($conds)
  )));

  try {
    return $mysqli
      ->execute_query($sql, array_values($conds))
      ->fetch_assoc();
  } catch (\Throwable $th) {
    return $th->getMessage();
  };
}

function setDb($user_id, $col, $value) {
  // todo
  return $value;
}

function dbDelete($user_id) {
  // todo
}
