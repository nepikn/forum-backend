<?php
$allowed_origins = ['http://localhost:5173'];

function respond($body, $code = 200) {
  array_walk(
    $GLOBALS['allowed_origins'],
    fn ($origin) => header("Access-Control-Allow-Origin: $origin", false)
  );
  header('Content-Type: application/json; charset=utf-8', true, $code);

  echo json_encode($body);
}
