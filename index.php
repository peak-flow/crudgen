<?php

use Pecee\SimpleRouter\SimpleRouter;

require __DIR__.'/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* Load external routes file */
require_once 'routes.php';

/**
 * The default namespace for route-callbacks, so we don't have to specify it each time.
 * Can be overwritten by using the namespace config option on your routes.
 */

SimpleRouter::setDefaultNamespace('\Davidany\CodeGen');

// Start the routing
SimpleRouter::start();
