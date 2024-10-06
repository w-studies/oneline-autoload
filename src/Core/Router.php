<?php

namespace Core;

class Router
{
  private static array $routes = [];

  private static string $prefix = '';

  private static string $namespaceModule = '';

  private static string $module = '';

  private static string $path = '';

  private static string $method = '';

  private static $params = [];

  public static function add(string $method, string $path, string|array|callable $handler)
  {

    $path = self::normalizeURL(self::$prefix . '/' . $path);

    $path = self::regexParse($path);

    self::$routes[] = [
      'path'       => $path,
      'method'     => strtoupper($method),
      'handler'    => $handler,
      'middleware' => null,
    ];

    return self::$routes;
  }

  public static function routes()
  {
    return self::$routes;
  }

  public static function getModule()
  {
    return self::$module;
  }

  public static function getNameSpaceModule()
  {
    return self::$namespaceModule;
  }

  public static function current()
  {
    return (object) ['path' => self::$path, 'method' => self::$method, 'params' => self::$params];
  }

  public static function dispatch($uri, $method)
  {
    self::$path   = self::normalizeURL($uri['path']);
    self::$method = strtoupper($method);

    self::$params = [];

    foreach (self::$routes as $route) {

      if ($route['method'] !== self::$method) {
        continue;
      }

      if (preg_match($route['path'], self::$path, $matches)) {
        foreach ($matches as $k => $v) {
          if (is_string($k)) {
            self::$params[$k] = $v;
          }
        }

        return self::handleHandler($route['handler']);
      }
    }

    echo self::$path . '<pre>Route not found!';
    print_r('<p>abort!</p>');
    echo '</pre>';
  }

  private static function handleHandler($handler)
  {

    if (is_callable($handler)) {
      // Se o handler for uma função, simplesmente chame-a
      return call_user_func($handler);
    }

    if (is_string($handler)) {
      $handler = explode('@', $handler);

      [$controller, $method] = $handler + [1 => 'index'];

      self::$module = $controller;

      self::$namespaceModule = "modules\\$controller\\$controller";
    } elseif (is_array($handler)) {
      [$controller, $method] = $handler + [1 => 'index'];

      self::$namespaceModule = $controller;

      // define o nome do meio como sendo o nome do módulo
      // exemplo: modules\Module\Controller
      self::$module = preg_replace('/.+\\\\(.+)\\\\.+/', '$1', $controller);
    }

    $controller = new self::$namespaceModule();
    $controller->$method(...self::$params);
  }

  public static function group(string $prefix, callable $callback)
  {
    self::setPrefix($prefix);
    call_user_func($callback);
    self::setPrefix();
  }

  public static function setPrefix(string $prefix = '')
  {
    self::$prefix = $prefix;

    return new static();
  }

  public static function get(string $path, string|array|callable $handler)
  {
    return self::add('GET', $path, $handler);
  }

  public static function post(string $path, string|array|callable $handler)
  {
    return self::add('POST', $path, $handler);
  }

  public static function patch(string $path, string|array|callable $handler)
  {
    return self::add('PATCH', $path, $handler);
  }

  private static function regexParse(string $string)
  {
    // escape forward slashes
    $regPath = preg_replace('/\//', '\\/', $string);

    // convert variables
    $regPath = preg_replace('/\#([a-z-]+)\#/', '(?P<\1>[0-9a-z-]+)', $regPath);

    // convert variables with regex
    $regPath = preg_replace('/\#([a-z-]+):([^#]+)\#/', '(?P<\1>\2)', $regPath);

    // add start/end delimiters and insensitive case
    $regPath = '/^' . $regPath . '$/i';

    return $regPath;
  }

  private static function normalizeURL(string $url): string
  {
    $normalizedURL = normalizer_normalize(trim($url, '/'), \Normalizer::FORM_D);
    $cleanedURL    = preg_replace('/[\p{M}\p{Zs}]/u', '', strtolower($normalizedURL));
    $path          = preg_replace('#[/]{2,}#', '/', "/{$cleanedURL}/");

    return $path;
  }
}
