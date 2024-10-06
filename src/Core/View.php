<?php

declare(strict_types=1);

namespace Core;

use App\Config;

class View
{
  private static string $layout = 'default';

  private static string $viewPath;

  public static function render(string $view, array $viewReveivedData = [])
  {
    self::viewPath($view);
    extract($viewReveivedData);
    unset($viewReveivedData, $view);

    $viewFile = self::$viewPath;

    // load template
    require Config::$PATHS->LAYOUTS . self::$layout . '.php';
  }

  private static function viewPath(string $view)
  {
    if(strpos($view, '/') === false) {
      return  self::$viewPath = Config::$PATHS->MODULES . Router::getModule() . '/views/' . $view . '.php';
    }
    $viewArray = explode('/', $view . '.php');

    array_splice($viewArray, 1, 0, 'views');

    $viewPath = implode('/', $viewArray);

    self::$viewPath = Config::PATHS()->MODULES . ucfirst($viewPath);
  }

  public static function setLayout(string $layout)
  {
    self::$layout = $layout;
  }
}
