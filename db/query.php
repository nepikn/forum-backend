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
    return $this->handleQuery(
      "INSERT INTO $this->table (%s) VALUES (%s)",
      [
        'format_vals' => [
          join(', ', array_keys($props)),
          '?' . str_repeat(', ?', count($props) - 1)
        ],
        'params' => array_values($props),
      ],
      fn () => $this->query('SELECT LAST_INSERT_ID() AS id')['id'],
    );
  }

  function get($conds = [], $cols = null) {
    $conds = count($conds) ? $conds : $this->default_conds;
    $col = is_array($cols) ? join(', ', $cols) : $cols ?? '*';

    return $this->handleQuery(
      "SELECT $col FROM $this->table",
      [
        'conds' => $conds,
      ],
      function ($result) use ($cols, $col) {
        return is_array($cols) && count($cols) == 1 ? $result[$col] : $result;
      },
    );
  }

  function update($props, $conds) {
    $assignment = join(', ', array_map(
      fn ($key) => "$key = ?",
      array_keys($props)
    ));

    return $this->handleQuery(
      "UPDATE $this->table SET %s",
      [
        'format_vals' => [$assignment],
        'params' => array_values($props),
        'conds' => $conds,
      ],
      function () use ($props) {
        return count($props) == 1 ? array_values($props)[0] : $props;
      }
    );
  }

  function delete($id) {
    return $this->handleQuery(
      "DELETE FROM $this->table",
      [
        'conds' => ['id' => $id],
      ],
    );
  }

  function handleQuery($sql_format, $options = [], callable $getResource = null) {
    $val_or_err = $this->query($sql_format, $options);

    return $val_or_err instanceof \Throwable || empty($getResource) ?
      $val_or_err
      : $getResource($val_or_err);
  }

  function query($sql_format, $options = []) {
    global $mysqli;
    @[
      'format_vals' => $format_vals,
      'params' => $params,
      'conds' => $conds,
    ] = $options;

    $sql = $format_vals ? sprintf($sql_format, ...$format_vals) : $sql_format;
    if ($conds && count($conds)) {
      [$sql, $params] = Sql::where($sql, $params ?? [], $conds);
    }

    try {
      $result = $params ?
        $mysqli->execute_query($sql, $params)
        : $mysqli->query($sql);
    } catch (\Throwable $th) {
      return $th;
    }

    return $result === false ?
      new Exception()
      : ($result === true ? null : $result->fetch_assoc());
  }
}

class Sql {
  static function where($sql, $params, $conds) {
    return [
      sprintf('%s WHERE %s', $sql, join(' AND ', array_map(
        fn ($key) => "$key = ?",
        array_keys($conds)
      ))),
      [...$params, ...array_values($conds)]
    ];
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
