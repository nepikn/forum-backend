<?php
require './util/env.php';

loadEnv('.env');

chdir('./routes');
preg_match('/\/([^\/]+)/', str_replace(
  getenv('API_BASE'),
  '',
  parse_url($_SERVER['REQUEST_URI'])['path']
), $matches);
require "$matches[1].php";
