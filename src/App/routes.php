<?php

use Core\Router;

Router::get('/', 'Home');
Router::get('/tickets', [modules\Tickets\Tickets::class]);
Router::post('/tickets', [modules\Tickets\Tickets::class, 'store']);
Router::get('/find-trips', [modules\Tickets\Tickets::class, 'trips']);
