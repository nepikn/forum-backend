<?php
header('content-type: text/plain');

class Router
{
  public $req = [];

  function __construct($path_base)
  {
    preg_match(
      '/([^?]+)(?:$|\?(.+))/',
      str_replace($path_base, "", $_SERVER['REQUEST_URI']),
      $req_matches
    );

    $this->req['path'] = $req_matches[1];
    $this->req['queries'] = [];

    if (@$req_matches[2]) {
      foreach (explode('&', $req_matches[2]) as $query) {
        preg_match('/(\w+)=?(\w*)/', $query, $matches);

        $this->req['queries'][$matches[1]] = $matches[2];
      }
    }
  }

  function get($route, $handle)
  {
    $pattern = sprintf(
      '/^%s$/',
      str_replace('/', '\/', preg_replace(
        '/\/\:(\w+)/',
        '/(?<$1>\w+)',
        $route
      ))
    );

    $this->handleReq(strtoupper(__FUNCTION__), $pattern, $handle);
  }

  function handleReq($method, $pattern, $handle)
  {
    if ($method != $_SERVER['REQUEST_METHOD']) return;

    preg_match(
      $pattern,
      // '/\/(?<id>\w+)/',
      $this->req['path'],
      $matches,
      // PREG_UNMATCHED_AS_NULL
    );

    if (!count($matches)) return;

    $req = [
      'args' => count($matches) ? array_filter(
        $matches,
        fn ($key) => is_string($key),
        ARRAY_FILTER_USE_KEY
      ) : null,
      ...$this->req
    ];

    $handle($req);
    // var_export($matches);
  }
}
