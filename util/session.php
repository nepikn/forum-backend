<?php
$testing = apache_request_headers()['User-Agent'] == "HTTPie";
$test_user = [
  'id' => 34,
  'name' => 'ok',
  'err' => false,
];

if (!$testing) {
  session_start();
}
header('Access-Control-Allow-Credentials: true');

function getSessionUser(string $prop = null) {
  global $testing, $test_user;
  $user = $testing ?
    $test_user
    : ($_SESSION['user'] ??= [
      'id' => null,
      'name' => null,
      'err' => false,
    ]);

  return $prop ? $user[$prop] : $user;
}

function setSessionUser($key, $value) {
  global $testing;

  if (!$testing) {
    $_SESSION['user'][$key] = $value;
  }

  return $value;
}

function delSessionUser() {
  // unset($_SESSION['user']);
  session_destroy();
  // return 200;
}
