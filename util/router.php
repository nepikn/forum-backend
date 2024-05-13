<?php
header('content-type: text/plain');

class Router {
  private $req = ['path' => '', 'queries' => null];
  public $valid_methods = ['POST', 'GET', 'PUT', 'DELETE'];

  function __construct(string $path_base) {
    $url = parse_url(str_replace($path_base, "", $_SERVER['REQUEST_URI']));
    // var_export($url);
    // preg_match(
    //   '/(?<path>[^?]*)($|\?(?<queries>.+))/',
    //   ,
    //   $req_matches
    // );

    // $this->req['path'] = @$url['path'];
    // $this->req['queries'] = @$url['query']?parse_str();
    if (@$url['path']) {
      $this->req['path'] = $url['path'];
    }
    if (@$url['query']) {
      parse_str($url['query'], $this->req['queries']);
    }

    // if (@$req_matches['queries']) {
    //   foreach (explode('&', $req_matches['queries']) as $query) {
    //     preg_match('/(\w+)=?(\w*)/', $query, $matches);

    //     $this->req['queries'][$matches[1]] = $matches[2];
    //   }
    // }
  }

  // function get($route, $handle)
  // {

  //   // $this->handleReq(strtoupper(__FUNCTION__), $pattern, $handle);
  // }

  // function handleReq($method, $pattern, $handle)
  function __call($method, $args) {
    if (!in_array(strtoupper($method), $this->valid_methods)) {
      throw new Exception('Invalid Method');
    }
    if (strtoupper($method) != $_SERVER['REQUEST_METHOD']) {
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

    $req = [
      'args' => array_filter(
        $matches,
        fn($key) => is_string($key),
        ARRAY_FILTER_USE_KEY
      ),
      ...$this->req,
    ];

    $handle($req);
    // var_export($req);
  }
}
