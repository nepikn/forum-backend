<?php
require_once '../util/controller.php';

class SessionController extends Controller {
  function __construct() {
    $this->db = new Db('users', [
      'name' => getSessionUser('name')
    ]);
  }

  function post() {
    $queries = $this->req['queries'];
    $db = $this->db->get();
    $matched = password_verify($queries['passwd'], $db['password']);

    setSessionUser('id', $matched ? $db['id'] : null);
    setSessionUser('err', !$matched);

    parent::respond($matched);
  }

  function get() {
    switch ($this->req['args']['prop']) {
      case 'authState':
        parent::respond(
          getSessionUser('err') ?
            'err'
            : ($this->db->get() === null ?
              'signUp'
              : 'signIn')
        );
        break;

      default:
        respond(null, 400);
        break;
    }
  }

  function put() {
    ['queries' => $queries] = $this->req;

    if (!count($queries)) {
      respond("no queries", 400);
    } else if (getSessionUser('id') !== null) {
      respond("has user id", 400);
    } else {
      parent::respond(setSessionUser('name', $queries['name']));
    }
  }

  function delete() {
    parent::respond(delSessionUser());
  }
}
