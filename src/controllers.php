<?php
/**
 * @var \Silex\Application $app
 */

$app->get('/', 'Controller\HomeController::index');
$app->get('/asset/list', 'Controller\AssetController::list');
$app->get('/asset/toevoegen', 'Controller\AssetController::toevoegen');
$app->get('/asset/bewerk/{id}', 'Controller\AssetController::bewerk');
$app->post('/asset/toevoegen', 'Controller\AssetController::toevoegen');
$app->post('/asset/bewerk/{id}', 'Controller\AssetController::bewerk');