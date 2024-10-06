<?php

use App\App;
use App\Config;

require __DIR__ . '/autoload.php';

// init config
Config::init();

// load routes
require __DIR__ . '/App/routes.php';

// start app
new App();
