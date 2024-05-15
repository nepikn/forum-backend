<?php
require_once '../db/conn.php';
require_once '../util/res.php';

class UserController {
  private $id = null;
  public $name;
  public $password;
  public mysqli $mysqli;

  function __construct() {
    session_start();
    header('Access-Control-Allow-Credentials: true');
    // unset($_SESSION['user_id']);
    // $_SESSION['user_id'] = 21;
    $id = @$_SESSION['user_id'];

    $this->id = $id;
    $this->mysqli = $GLOBALS['mysqli'];

    if ($id !== null) {
      $props = $this->getUserProp('*');
      foreach ($props as $key => $value) {
        $this->$key = $value;
      }
    }
  }

  private function getUserProp($prop, $id = null) {
    $id ??= $this->id;
    $sql = "SELECT $prop FROM users WHERE id = $id";

    return $this->mysqli
      ->query($sql)
      ->fetch_assoc();
  }

  function getName($req, $escape = false) {
    $req_id = @$req['queries']['id'];
    $result = $req_id === null ?
      $this->name
      : $this->getUserProp('name', $req_id);
    $name = $escape ? htmlspecialchars($result) : $result;

    return $name;
  }
}
