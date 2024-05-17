<?php

class Router {
  public $valid_methods = ['POST', 'GET', 'PUT', 'DELETE'];
  private $req = ['path' => '', 'queries' => null];
  private $responses = [];
  private $matched_routes = [];

  function __construct(string $path_base) {
    $url = parse_url(str_replace($path_base, "", $_SERVER['REQUEST_URI']));

    if (@$url['path']) {
      $this->req['path'] = $url['path'];
    }
    if (@$url['query']) {
      parse_str($url['query'], $this->req['queries']);
    }

    register_shutdown_function(function () {
      switch (count($this->responses)) {
        case 0:
          respond(404);
          break;

        case 1:
          respond($this->responses[0]);
          break;

        default:
          respond(
            'server error: requested path matching mutiple routes: ' .
              json_encode($this->matched_routes),
            500
          );
          break;
      }
    });
  }

  function __call($method, $args) {
    $method = strtoupper($method);

    if (!in_array($method, $this->valid_methods)) {
      array_push($this->responses, 400);
      return;
    }

    switch ($_SERVER['REQUEST_METHOD']) {
      case $method:
        break;
      case 'OPTIONS':
        if ($method == apache_request_headers()['Access-Control-Request-Method'])
          break;
      default:
        return;
    }

    [$route, $handle] = $args;

    preg_match(
      sprintf(
        '/^%s$/',
        str_replace('/', '\/', preg_replace(
          '/\/\:(\w+)/',
          '/(?<$1>\w+)',
          $route
        ))
      ),
      // '/\/(?<id>\w+)/',
      $this->req['path'],
      $matches,
      // PREG_UNMATCHED_AS_NULL
    );

    if (!count($matches)) {
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
      header("Access-Control-Allow-Methods: $method");
      array_push($this->responses, 200);
      return;
    }

    $req = [
      'args' => array_filter(
        $matches,
        fn ($key) => is_string($key),
        ARRAY_FILTER_USE_KEY
      ),
      ...$this->req,
    ];

    array_push($this->responses, $handle($req));
    array_push($this->matched_routes, $route);
    // var_export($req);
  }
}
