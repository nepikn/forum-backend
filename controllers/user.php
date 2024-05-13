<?php
require_once '../util/res.php';

class UserController {
  public $id;

  public function __construct($id) {
    $this->id = $id;
  }

  public function printName() {
    global $mysqli;
    $id = $this->id;

    // printJson('QAQ');
    printJson($id === null ? null : htmlspecialchars(
      $mysqli->query(vsprintf(
        'SELECT name FROM users WHERE id = %s',
        [$id]
      ))->fetch_column()
    ));
  }
}
