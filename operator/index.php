<?php
/*
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
*/

use Slim\Http\Request;
use Slim\Http\Response;

define('APPLICATION_ROOT', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
define('CONFIGURATION_ROOT', APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'configuration');
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_read.php');
define('DB_WRITE_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_write.php');

require APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

spl_autoload_register(function ($class) {
    require(APPLICATION_ROOT . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . $class . ".php");
});

$configuration['displayErrorDetails'] = true;
$configuration['addContentLengthHeader'] = false;

$container = new \Slim\Container(['settings' => $configuration]);
$container['view'] = new \Slim\Views\PhpRenderer(APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'view');

$app = new \Slim\App($container);

$app->get('/', function (Request $request, Response $response, array $arguments) {

    $router = $this->get('router');
    $page = new \Remittance\Web\OperatorPage($this,$router);
    $response = $page->root($request, $response, $arguments);

    return $response;
});

$app->post('/transfer/accomplish/{id}', function (Request $request, Response $response, array $arguments) {

    $router = $this->get('router');
    $page = new \Remittance\Web\OperatorPage($this,$router);
    $response = $page->accomplish($request, $response, $arguments);

    return $response;

})->setName(\Remittance\Web\OperatorPage::ACTION_ACCOMPLISH);

$app->post('/transfer/annul/{id}', function (Request $request, Response $response, array $arguments) {

    $router = $this->get('router');
    $page = new \Remittance\Web\OperatorPage($this,$router);
    $response = $page->annul($request, $response, $arguments);

    return $response;

})->setName(\Remittance\Web\OperatorPage::ACTION_ANNUL);

$app->run();
