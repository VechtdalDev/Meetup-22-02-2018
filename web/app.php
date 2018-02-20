<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../src/app.php';
$app['debug'] = true;

$app->run();