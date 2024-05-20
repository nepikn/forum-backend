<?php

class Controller {
  protected $req;
  public $id;

  function __invoke($method, $req) {
    // $id = @$req['queries']['id'] ?? $this->id;
    // $req_prop = @$req['args']['prop'];
    // @['id' => $id, 'prop' => $prop] = $req['args'];
    $this->req = $req;
    return $this->$method();
  }
}
