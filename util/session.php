<?php
function getSessionUser() {
  return $_SESSION['user'] ??= [
    'id' => null,
    'name' => null,
    'err' => false,
  ];
}

function setSessionUser($name, $value) {
  $_SESSION['user'][$name] = $value;
}
