<?php
require_once '../db/query.php';
require_once '../util/controller.php';
require_once '../util/res.php';
require_once '../util/session.php';

class UserController extends Controller {
  public $name;
  private $err;
  private $password;
  // private $src;

  function __construct() {
    // $this->src=getSessionUser('id')===null?;
    $session_user = getSessionUser();
    $this->id = $session_user['id'];
    // $session_user_id = $session_user['id'];
    // $props = $session_user_id ?
    //   $this->queryDb('*', $session_user_id)
    //   : $session_user;

    // foreach ($props as $key => $value) {
    //   // foreach ($session_user as $key => $value) {
    //   // foreach (getSessionUser() as $key => $value) {
    //   $this->$key = $value;
    // }
  }

  function get($escape = false) {
    [$user_id, $req_prop] = $this->getReqInfo();

    if ($user_id === null) {
      respond($req_prop == 'name' ? getSessionUser('name') : null);
      return;
    }

    $result = dbGet($user_id, $req_prop ?? '*');
    // $name = $escape ? htmlspecialchars($result) : $result;

    respond($escape ? htmlspecialchars($result) : $result);
  }

  function put() {
    [$user_id, $req_prop, $queries] = $this->getReqInfo();
    $value = @$queries['value'];

    if (in_array($req_prop, ['id'])) {
      respond("invalid prop: $req_prop", 400);
      return;
    }
    if (!$value) {
      respond("no value", 400);
      return;
    }

    // $value = json_decode($value);
    if ($user_id === null) {
      if ($req_prop != 'name') {
        respond("setting $req_prop while no user id is invalid", 400);
      } else {
        respond(setSessionUser('name', $value));
      }
      return;
    }

    respond(dbSet($user_id, $req_prop, $value));
  }

  function delete() {
    [$user_id] = $this->getReqInfo();

    if ($user_id === null) {
      respond(setSessionUser('name', null));
      return;
    }

    respond(dbDelete($user_id));
  }

  function getReqInfo() {
    $req = $this->req;
    return [
      @$req['args']['id'] ?? $this->id,
      @$req['args']['prop'],
      $req['queries']
    ];
  }

  // function handleNullIdUser(callable $handle_session_user) {
  //   [$user_id, $req_prop] = $this->getReqInfo();

  //   if ($user_id === null) {
  //     $handle_session_user($req_prop);
  //     // if ($req_prop == 'name') {
  //     //   respond($handle_session_user('name'));
  //     // }
  //     // respond(null);
  //     return true;
  //   }
  // }

  // function setName($req) {
  //   $name = $req['queries']['name'];

  //   $_SESSION['user_name'] = $name;
  //   // $_SESSION['user_name'] = '1';
  //   // setSessionUser('name', $name);
  //   // if ($this->id === null) {
  //   // } else {
  //   //   // todo
  //   // }

  //   return $_SESSION['user_name'];
  //   return getSessionUser()['name'];
  // }
}

class ClientUserController extends UserController {
  function __construct() {
    parent::__construct();
  }

  // function getProps() {
  //   return get_object_vars($this);
  // }
}
