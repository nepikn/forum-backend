<?php
$testing = empty($_GET['PHPSESSID']) && apache_request_headers()['User-Agent'] == "HTTPie";
$test_user = [
  'id' => 1,
  'name' => 'fox',
  'err' => false,
];

if (!$testing) {
  header('Access-Control-Allow-Credentials: true');

  if (str_starts_with(@apache_request_headers()['Origin'], 'http://localhost')) {
    ini_set('session.cookie_samesite', 'None');
    ini_set('session.cookie_secure', 'On');
  }

  session_start();
}

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
}

function delSessionUser() {
  // unset($_SESSION['user']);
  session_destroy();
  // return 200;
}
