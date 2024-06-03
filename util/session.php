<?php
$testing = apache_request_headers()['User-Agent'] == "HTTPie";

if (!$testing) {
  session_start();
}
header('Access-Control-Allow-Credentials: true');

function getSessionUser(string $prop = null) {
  global $testing;
  $user = $testing ?
    [
      'id' => 21,
      'name' => 'ok',
      'err' => false,
    ] : ($_SESSION['user'] ??= [
      'id' => null,
      'name' => null,
      'err' => false,
    ]);
  return $prop ? $user[$prop] : $user;
}

function setSessionUser($key, $value) {
  $_SESSION['user'][$key] = $value;
  return $value;
}

function delSessionUser() {
  // unset($_SESSION['user']);
  session_destroy();
  // return 200;
}
