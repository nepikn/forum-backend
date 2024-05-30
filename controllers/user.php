<?php
require_once '../db/query.php';
require_once '../util/controller.php';
require_once '../util/res.php';
require_once '../util/session.php';

class UserController extends Controller {
  function __construct() {
    $this->id = getSessionUser('id');
  }

  function get($escape = false) {
    [$user_id, $req_prop, $queries] = $this->getReqInfo();

    if ($user_id === null) {
      switch ($req_prop) {
        case 'authState':
          respond(getSessionUser('err') ?
            'err'
            : getDb(['name' => getSessionUser('name')]) !== null);
          return;

        default:
          respond(getSessionUser($req_prop));
          return;
      }
    }

    $result = getDb(count($queries) ? $queries : $user_id, $req_prop);

    respond($escape ? htmlspecialchars($result) : $result);
  }

  function put() {
    [$user_id, $req_prop, $queries] = $this->getReqInfo();
    $value = @$queries['value'];

    if (!$value) {
      respond("no value", 400);
    } else if ($req_prop == 'id') {
      $db = getDb(['name' => getSessionUser('name')]);
      $matched = password_verify($queries['passwd'], $db['password']);

      setSessionUser('id', $matched ? $db['id'] : null);
      setSessionUser('err', !$matched);

      respond();
    } else if ($user_id === null) {
      if ($req_prop != 'name') {
        respond("setting $req_prop while no user id is invalid", 400);
      } else {
        respond(setSessionUser('name', $value));
      }
    } else {
      respond(setDb($user_id, $req_prop, $value));
    }
  }

  function delete() {
    [$user_id] = $this->getReqInfo();

    if ($user_id === null) {
      respond(delSessionUser());
    } else {
      respond(dbDelete($user_id));
    }
  }

  function getReqInfo() {
    $req = $this->req;
    return [
      @$req['args']['id'] ?? $this->id,
      @$req['args']['prop'],
      $req['queries']
    ];
  }
}

class ClientUserController extends UserController {
  function __construct() {
    parent::__construct();
  }

  // function getProps() {
  //   return get_object_vars($this);
  // }
}
