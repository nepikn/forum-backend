<?php

function respond($body = null, $code = 200, $headers = []) {
  $origin = @apache_request_headers()['Origin'];
  if (isset($origin)) {
    $allowed_origins = explode(',', getenv('ALLOWED_ORIGINS'));
    if (!in_array($origin, $allowed_origins)) return;

    header("Access-Control-Allow-Origin: $origin");
  }

  header('Content-Type: application/json; charset=utf-8', true, $code);
  array_walk(
    $headers,
    fn ($header) => header($header, false)
  );

  echo json_encode($body);
}
