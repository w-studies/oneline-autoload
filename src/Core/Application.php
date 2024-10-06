<?php

declare(strict_types=1);

namespace Core;

class Application
{
  public function __construct()
  {
    require __DIR__ . '/helpers/functions.php';
    require __DIR__ . '/helpers/httpResponse.php';

    $url    = parse_url($_SERVER['REQUEST_URI']);
    $method = $_SERVER['REQUEST_METHOD'];

    try {
      Router::dispatch($url, $method);
    } catch (\Exception $e) {
      echo $e->getMessage();

      echo '<pre>$e: ';
      print_r($e);
      echo '</pre>';
    }
  }
}
