<?php
$allowed_origins = ['http://localhost:5173'];

function printJson($body) {
  header('Content-Type: application/json; charset=utf-8');
  array_walk(
    $GLOBALS['allowed_origins'],
    fn($origin) => header("Access-Control-Allow-Origin: $origin", false)
  );

  echo json_encode($body);
}
