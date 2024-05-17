<?php
require_once '../db/conn.php';
require_once '../util/res.php';
require_once '../util/session.php';

class UserController {
  public $id;
  public $name;
  private $err;
  private $password;
  // private mysqli $mysqli;

  function __construct() {
    session_start();
    header('Access-Control-Allow-Credentials: true');
    // unset($_SESSION['user_id']);
    // $_SESSION['user_id'] = 21;

    $session_user = getSessionUser();
    $session_user_id = $session_user['id'];
    $props = $session_user_id ?
      $this->queryDb('*', $session_user_id)
      : $session_user;

    foreach ($props as $key => $value) {
      $this->$key = $value;
    }
    // if ($id === null) {
    //   $this->name = @$_SESSION['user_name'];
    // } else {
    //   $props = $this->queryDb('*');

    // }
  }

  private function queryDb($prop, $id = null) {
    $id ??= $this->id;
    $sql = "SELECT $prop FROM users WHERE id = $id";

    return $GLOBALS['mysqli']
      ->query($sql)
      ->fetch_assoc();
  }

  function getName($id_or_req, $escape = false) {
    $req_id = is_string($id_or_req) ? $id_or_req : @$id_or_req['queries']['id'];
    $result = $req_id === null ?
      $this->name
      : $this->queryDb('name', $req_id);
    $name = $escape ? htmlspecialchars($result) : $result;

    return $name;
  }

  function putName($req) {
    $name = $req['queries']['name'];

    if ($this->id === null) {
      setSessionUser('name', $name);
    } else {
      // todo
    }

    return getSessionUser()['name'];
  }
}

class ClientUserController extends UserController {
  function __construct() {
    parent::__construct();
  }

  function getProps() {
    return get_object_vars($this);
  }
}
