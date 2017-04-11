<?php

use Remittance\Web\ManagerPage;
use Slim\Http\Request;
use Slim\Http\Response;

define('APPLICATION_ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');

define('CONFIGURATION_ROOT', APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'configuration');
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_read.php');
define('DB_WRITE_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_write.php');

require APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'autoload_classes.php';
require APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$configuration['displayErrorDetails'] = true;
$configuration['addContentLengthHeader'] = false;

$container = new \Slim\Container(['settings' => $configuration]);

const ROUTER_COMPONENT = 'router';
const VIEWER_COMPONENT = 'view';
$container[VIEWER_COMPONENT] = new \Slim\Views\PhpRenderer(APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'view');

$app = new \Slim\App($container);

$app->get('/', function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new ManagerPage($viewer, $router);

    $response = $page->root($request, $response, $arguments);

    return $response;
});

$app->post('/currency/add', function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->add($request, $response, $arguments);

    return $response;

});

$app->post('/currency/' . ManagerPage::ACTION_DISABLE . '/{id}', function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->disable($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_DISABLE);

$app->post('/currency/' . ManagerPage::ACTION_ENABLE . '/{id}', function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->enable($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_ENABLE);

$app->run();
