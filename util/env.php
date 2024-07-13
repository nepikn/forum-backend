<?php

function loadEnv($path) {
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (str_starts_with(trim($line), '#')) {
      continue;
    }

    [$key, $val] = array_map('trim', explode('=', $line, 2));

    if (getenv($key) === false) {
      putenv("$key=$val");
    }
  }
}
