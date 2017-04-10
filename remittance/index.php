<?php

/*
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
*/

use Slim\Http\Request;
use Slim\Http\Response;

define('APPLICATION_ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..');

define('CONFIGURATION_ROOT', APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'configuration');
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_read.php');
define('DB_WRITE_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_write.php');

require APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'autoload_classes.php';

$configuration['displayErrorDetails'] = true;
$configuration['addContentLengthHeader'] = false;

$container = new \Slim\Container(['settings' => $configuration]);

const VIEWER_COMPONENT = 'view';
$container[VIEWER_COMPONENT] = new \Slim\Views\PhpRenderer(APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'view');

$app = new \Slim\App($container);

$app->get('/', function (Request $request, Response $response, array $arguments) {

    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new \Remittance\Web\CustomerPage($viewer);

    $response = $page->root($request, $response, $arguments);

    return $response;
});

$app->post('/order/add', function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\CustomerApi();
    $response = $api->add($request, $response, $arguments);

    return $response;

});

$app->run();
