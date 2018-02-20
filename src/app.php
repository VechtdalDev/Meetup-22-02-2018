<?php

use Silex\Application;

$app = new Application();

require_once __DIR__ . '/services.php';
require_once __DIR__ . '/controllers.php';

return $app;