<?php
require_once '../db/conn.php';

class Db {
  private $table;
  private $default_conds;

  function __construct($table, $default_conds = []) {
    $this->table = $table;
    $this->default_conds = $default_conds;
  }

  function insert($props) {
    $this->query(
      'INSERT INTO %s (%s) VALUES (%s)',
      [
        $this->table,
        join(', ', array_keys($props)),
        '?' . str_repeat(', ?', count($props) - 1)
      ],
      array_values($props)
    );

    return $this->query('SELECT LAST_INSERT_ID() AS id', bind: false)['id'];
  }

  function get($conds = [], $cols = [],) {
    $col = count($cols) ? join(', ', $cols) : '*';
    $conds = count($conds) ? $conds : $this->default_conds;

    $result = $this->query(
      "SELECT $col FROM %s %s",
      [
        $this->table,
        Sql::where($conds)
      ],
      array_values($conds)
    );

    // return [$cols, $conds];
    return count($cols) == 1 ? $result[$col] : $result;
  }

  function setDb($user_id, $col, $value) {
    // todo
  }

  function dbDelete($user_id) {
    // todo
  }

  function query($sql_format, $format_vals = [], $params = [], $bind = true) {
    global $mysqli;

    $sql = count($format_vals) ? sprintf($sql_format, ...$format_vals) : $sql_format;

    try {
      return ($bind ?
        $mysqli->execute_query($sql, $params)
        : $mysqli->query(sprintf($sql, $params)))
        ->fetch_assoc();
    } catch (\Throwable $th) {
      return $th->getMessage();
    };
  }
}

class Sql {
  static function where($conds) {
    return count($conds) ?
      'WHERE ' . join(' AND ', array_map(
        fn ($key) => "$key = ?",
        array_keys($conds)
      ))
      : '';
  }
}

function query($sql, ...$values) {
  global $mysqli;
  return $mysqli->query(count($values) ? sprintf($sql, ...$values) : $sql);
}

function insertDb($props) {
  queryDb(
    'INSERT INTO users (%s) VALUES (%s)',
    [join(', ', array_keys($props)), '?' . str_repeat(', ?', count($props) - 1)],
    array_values($props)
  );

  return queryDb('SELECT LAST_INSERT_ID() AS id', bind: false)['id'];
}

function getDb($id_or_user_cond, ...$cols) {
  $conds = is_array($id_or_user_cond) ? $id_or_user_cond : ['id' => $id_or_user_cond];
  $col = $cols ? join(', ', $cols) : '*';
  $result = queryDb(
    "SELECT $col FROM users WHERE %s",
    [join(' AND ', array_map(
      fn ($key) => "$key = ?",
      array_keys($conds)
    ))],
    array_values($conds)
  );

  return count($cols) == 1 ? $result[$col] : $result;
}

function setDb($user_id, $col, $value) {
  // todo
}

function dbDelete($user_id) {
  // todo
}

function queryDb($sql_format, $format_vals = [], $params = [], $bind = true) {
  global $mysqli;

  $sql = count($format_vals) ? sprintf($sql_format, ...$format_vals) : $sql_format;

  try {
    return ($bind ?
      $mysqli->execute_query($sql, $params)
      : $mysqli->query(sprintf($sql, $params)))
      ->fetch_assoc();
  } catch (\Throwable $th) {
    return $th->getMessage();
  };
}
