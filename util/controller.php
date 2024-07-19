<?php
require_once '../db/query.php';
require_once '../util/res.php';
require_once '../util/session.php';

class Controller {
  public $id;
  protected Db $db;
  protected $req;

  function __invoke($method, $req) {
    if (
      apache_request_headers()['User-Agent'] != "HTTPie"
      && in_array($method, ['POST', 'PUT', 'DELETE'])
      && empty($_COOKIE['PHPSESSID'])
    ) {
      respond('no session id', 400);
      return;
    }

    $this->req = $req;
    return $this->$method();
  }

  function respond($val_or_err = null) {
    if ($val_or_err instanceof \Throwable) {
      respond(@$val_or_err->getMessage(), 500);
    } else {
      respond($val_or_err);
    }
  }
}
