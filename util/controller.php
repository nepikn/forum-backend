<?php

class Controller {
  protected $req;
  public $id;

  function __invoke($method, $req) {
    // $id = @$req['queries']['id'] ?? $this->id;
    // $req_prop = @$req['args']['prop'];
    // @['id' => $id, 'prop' => $prop] = $req['args'];
    if (
      in_array($method, ['POST', 'PUT', 'DELETE'])
      && empty($_COOKIE['PHPSESSID'])
    ) {
      respond('no session id', 400);
      return;
    }

    $this->req = $req;
    return $this->$method();
  }
}
