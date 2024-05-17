<?php
$allowed_origins = ['http://localhost:5173'];

function respond($body_or_code, $code = 200) {
  array_walk(
    $GLOBALS['allowed_origins'],
    fn ($origin) => header("Access-Control-Allow-Origin: $origin", false)
  );

  if (is_numeric($body_or_code)) {
    http_response_code($body_or_code);
    return;
  }

  header('Content-Type: application/json; charset=utf-8', true, $code);

  echo json_encode($body_or_code);
}
