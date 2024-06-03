<?php
require_once '../util/controller.php';

class UserController extends Controller {
  function __construct() {
    $this->db = new Db('users');
  }

  function post() {
    $passwd = $this->req['queries']['passwd'];
    $id = $this->db->insert([
      'name' => getSessionUser('name'),
      'password' => password_hash($passwd, PASSWORD_DEFAULT)
    ]);

    setSessionUser('id', $id);
    respond($id);
  }

  function get($escape = false) {
    [$user_id, $req_prop, $queries] = $this->getReqInfo();

    if ($user_id === null) {
      respond(getSessionUser($req_prop));
    } else {
      $result = $this->db->get(
        count($queries) ? $queries : ['id' => $user_id],
        [$req_prop],
      );

      respond($escape ? htmlspecialchars($result) : $result);
    }
  }

  function put() {
    [$user_id, $req_prop, $queries] = $this->getReqInfo();
    $value = @$queries['value'];

    if (!$value || $req_prop == 'id') {
      respond("no value or setting id", 400);
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
      respond('no user id', 400);
    } else {
      // respond(dbDelete($user_id));
    }
  }

  function getReqInfo() {
    $req = $this->req;
    return [
      @$req['args']['id'] ?? getSessionUser('id'),
      @$req['args']['prop'],
      $req['queries']
    ];
  }
}
