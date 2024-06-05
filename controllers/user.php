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
    parent::respond($id);
  }

  function get($escape = false) {
    [$user_id, $req_prop, $queries] = $this->getReqInfo();

    if ($user_id === null) {
      parent::respond(getSessionUser($req_prop));
    } else {
      $result = $this->db->get(
        count($queries) ? $queries : ['id' => $user_id],
        [$req_prop],
      );

      parent::respond($result);
      // parent::respond($escape ? htmlspecialchars($result) : $result);
    }
  }

  function put() {
    [$user_id,, $queries] = $this->getReqInfo();

    if (!count($queries)) {
      respond("no queries", 400);
    } else if ($user_id === null) {
      respond("no user id", 400);
    } else {
      parent::respond($this->db->update(
        $queries,
        ['id' => $user_id]
      ));
    }
  }

  function delete() {
    [$user_id] = $this->getReqInfo();

    if ($user_id === null) {
      respond('no user id', 400);
    } else {
      // parent::respond(dbDelete($user_id));
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
