<?php
try {
  $mysqli = new mysqli('localhost', 'root', '', 'forum');
} catch (\Throwable $th) {
  try {
    $mysqli = new mysqli('localhost', 'admin', 'auth_string', 'forum');
  } catch (\Throwable $th) {
    echo $th->getMessage();
    exit;
  }
}
