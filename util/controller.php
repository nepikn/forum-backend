<?php

class Controller {
  function __invoke($method, $req) {
    return $this->$method($req);
  }
}
