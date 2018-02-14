<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../src/app.php';
$app['debug'] = true;

require_once __DIR__ . '/../src/services.php';
require_once __DIR__ . '/../src/controllers.php';

$app->run();