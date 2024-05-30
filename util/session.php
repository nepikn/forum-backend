<?php
session_start();
// unset($_SESSION['user']);
header('Access-Control-Allow-Credentials: true');

function getSessionUser(string $prop = null) {
  $user = $_SESSION['user'] ??= [
    'id' => null,
    'name' => null,
    'err' => false,
  ];
  return $prop ? $user[$prop] : $user;
}

function setSessionUser($name, $value) {
  $_SESSION['user'][$name] = $value;
  return $value;
}

function delSessionUser() {
  unset($_SESSION['user']);
}
