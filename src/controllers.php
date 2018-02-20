<?php
/**
 * @var \Silex\Application $app
 */

$app->get('/', 'Controller\HomeController::index');
$app->get('/asset/list', 'Controller\AssetController::list');