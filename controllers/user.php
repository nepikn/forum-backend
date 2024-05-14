<?php
require_once '../util/res.php';

class UserController {
  public $id;

  public function __construct($id) {
    $this->id = $id;
  }

  public function getName($req) {
    global $mysqli;
    // $id = $this->id;
    $id = @$req->queries['id'];
    $name = $id === null ? null : htmlspecialchars(
      $mysqli->query("SELECT name FROM users WHERE id = $id")->fetch_column()
    );

    return $name;
  }
}
