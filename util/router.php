<?php

class Router {
  static $valid_methods = ['POST', 'GET', 'PUT', 'DELETE'];
  private $req = [];
  private $controller;
  private $responded = false;
  private $handles = [];
  // private $responses = [];
  private $matched_routes = [];

  function __construct(string $path_base, $controller = null) {
    // ob_start();
    $url = parse_url(str_replace($path_base, "", $_SERVER['REQUEST_URI']));

    $this->req['path'] = @$url['path'] ?? '';
    if (@$url['query']) {
      parse_str($url['query'], $this->req['queries']);
    } else {
      $this->req['queries'] = [];
    }
    $this->controller = $controller;

    register_shutdown_function([$this, 'handleRes']);
  }

  function __call($route_method, $args) {
    if ($this->responded) return;

    $route_method = strtoupper($route_method);
    if ($this->handleInvalidMethod($route_method)) return;
    if ($this->handleUnmatchedMethod($route_method)) return;

    [$route] = $args;
    $handle = @$args[1] ?? $this->controller;

    $matches = $this->getRoutePregMatch($route);
    if (!count($matches)) return;

    $this->req['args'] = array_filter(
      $matches,
      fn ($key) => is_string($key),
      ARRAY_FILTER_USE_KEY
    );
    // $req = [
    //   'args' => array_filter(
    //     $matches,
    //     fn ($key) => is_string($key),
    //     ARRAY_FILTER_USE_KEY
    //   ),
    //   ...$this->req,
    // ];

    // array_push($this->responses, $handle($route_method, $req));
    array_push($this->handles, $handle);
    array_push($this->matched_routes, $route);
    // var_export($req);
  }

  function handleInvalidMethod($route_method) {
    if (in_array($route_method, self::$valid_methods)) return;

    respond("invalid route method: $route_method", 500);
    $this->responded = true;
    return true;
  }

  function handleUnmatchedMethod($route_method) {
    switch ($_SERVER['REQUEST_METHOD']) {
      case $route_method:
        return;

      case 'OPTIONS':
        if ($route_method == @apache_request_headers()['Access-Control-Request-Method']) {
          // header("Access-Control-Allow-Methods: $route_method");
          respond(headers: ["Access-Control-Allow-Methods: $route_method"]);
          $this->responded = true;
        };
      default:
        return true;
    }
  }

  function getRoutePregMatch($route) {
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

    return $matches;
  }

  function handleRes() {
    if ($this->responded) return;
    // respond('qq');
    // return;
    // if ($err = ob_get_clean()) {
    //   respond($err);
    //   return;
    // }

    switch (count($this->handles)) {
        // switch (count($this->responses)) {
      case 0:
        $path = $this->req['path'];
        respond("no such path: $path", 404);
        break;

      case 1:
        // $res = $this->responses[0];
        $handle = $this->handles[0];
        $handle($_SERVER['REQUEST_METHOD'], $this->req);
        break;

      default:
        respond(
          'requested path matching mutiple routes: ' .
            json_encode($this->matched_routes),
          500
        );
        break;
    }
  }
}
