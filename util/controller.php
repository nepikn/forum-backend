<?php
require_once '../db/query.php';
require_once '../util/res.php';
require_once '../util/session.php';

class Controller {
  public $id;
  protected Db $db;
  protected $req;

  function __invoke($method, $req) {
    // $id = @$req['queries']['id'] ?? $this->id;
    // $req_prop = @$req['args']['prop'];
    // @['id' => $id, 'prop' => $prop] = $req['args'];
    if (
      apache_request_headers()['User-Agent'] != "HTTPie"
      && in_array($method, ['POST', 'PUT', 'DELETE'])
      && empty($_COOKIE['PHPSESSID'])
    ) {
      respond('no session id', 400);
      return;
    }
    // var_export($this->$method);

    $this->req = $req;
    return $this->$method();
  }
}
