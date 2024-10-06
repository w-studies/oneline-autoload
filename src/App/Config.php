<?php

declare(strict_types=1);

namespace App;

class Config
{
  public static $PATHS;

  public static function init()
  {
    $modulesPath = '../src/modules/';
    self::$PATHS = (object) [
      'MODULES' => $modulesPath,
      'LAYOUTS' => $modulesPath . 'layouts/',
    ];

    return self::$PATHS;
  }
}
