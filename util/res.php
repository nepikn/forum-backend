<?php
$allowed_origins = ['http://localhost:5173'];

function respond($body = null, $code = 200, $headers = []) {
  array_walk(
    $GLOBALS['allowed_origins'],
    fn ($origin) => header("Access-Control-Allow-Origin: $origin", false)
  );
  array_walk(
    $headers,
    fn ($header) => header($header, false)
  );
  header('Content-Type: application/json; charset=utf-8', true, $code);

  echo json_encode($body);
}

function hasRespond() {
  // todo
}
