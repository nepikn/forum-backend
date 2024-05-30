<?php
require_once '../db/conn.php';

function query($sql, ...$values) {
  global $mysqli;
  return $mysqli->query(count($values) ? sprintf($sql, ...$values) : $sql);
}

function insertDb($vals, $cols = []) {
  // todo
  queryDb('INSERT INTO users VALUES (0, ?, ?)', $vals);
  return queryDb('SELECT LAST_INSERT_ID() AS id', [], false)['id'];
}

function getDb($id_or_user_cond, ...$cols) {
  $conds = is_array($id_or_user_cond) ? $id_or_user_cond : ['id' => $id_or_user_cond];
  $col = $cols ? join(', ', $cols) : '*';
  $sql = sprintf("SELECT $col FROM users WHERE %s", join(' AND ', array_map(
    fn ($key) => "$key = ?",
    array_keys($conds)
  )));
  $result = queryDb($sql, array_values($conds));

  return count($cols) == 1 ? $result[$col] : $result;
}

function setDb($user_id, $col, $value) {
  // todo
}

function dbDelete($user_id) {
  // todo
}

function queryDb($sql, $params = [], $bind = true) {
  global $mysqli;

  try {
    return ($bind ?
      $mysqli->execute_query($sql, $params)
      : $mysqli->query(sprintf($sql, $params)))
      ->fetch_assoc();
  } catch (\Throwable $th) {
    return $th->getMessage();
  };
}
