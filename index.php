<?php
require './util/env.php';

loadEnv('.env');

chdir('./routes');
require sprintf('.%s.php', str_replace(
  getenv('API_BASE'),
  '',
  parse_url($_SERVER['REQUEST_URI'])['path']
));
