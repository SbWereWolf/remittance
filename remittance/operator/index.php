<?php
/*
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
*/

use Remittance\Presentation\Web\IRoute;
use Remittance\Presentation\Web\OperatorApi;
use Remittance\Presentation\Web\OperatorPage;
use Slim\Http\Request;
use Slim\Http\Response;

define('APPLICATION_ROOT', realpath(__DIR__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');

define('CONFIGURATION_ROOT', APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'configuration');
define('DB_READ_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_read.php');
define('DB_WRITE_CONFIGURATION', CONFIGURATION_ROOT . DIRECTORY_SEPARATOR . 'db_write.php');

require APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'autoload_classes.php';

$configuration['displayErrorDetails'] = true;
$configuration['addContentLengthHeader'] = false;

$container = new \Slim\Container(['settings' => $configuration]);

const ROUTER_COMPONENT = 'router';
const VIEWER_COMPONENT = 'view';
$container[VIEWER_COMPONENT] = new \Slim\Views\PhpRenderer(APPLICATION_ROOT . DIRECTORY_SEPARATOR . 'view');

$app = new \Slim\App($container);

$app->get(OperatorPage::ROOT, function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new OperatorPage($viewer, $router);
    $response = $page->root($request, $response, $arguments);

    return $response;
});

$pathForTransferModule = OperatorPage::ROOT . OperatorPage::MODULE_TRANSFER;
$app->get($pathForTransferModule, function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new OperatorPage($viewer, $router);
    $response = $page->transfer($request, $response, $arguments);

    return $response;
})->setName(OperatorPage::MODULE_TRANSFER);

$pathForTransferEdit = OperatorPage::ROOT . implode(IRoute::PATH_SYMBOL,
        array(OperatorPage::MODULE_TRANSFER,
            OperatorPage::ACTION_TRANSFER_EDIT,
            '{' . OperatorPage::ID . '}'));
$app->get($pathForTransferEdit, function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new OperatorPage($viewer, $router);
    $response = $page->transferEdit($request, $response, $arguments);

    return $response;
})->setName(OperatorPage::ACTION_TRANSFER_EDIT);

$pathForTransferAccomplish = OperatorPage::ROOT . implode(IRoute::PATH_SYMBOL,
        array(OperatorPage::MODULE_TRANSFER,
            OperatorPage::ACTION_TRANSFER_ACCOMPLISH,
            '{' . OperatorPage::ID . '}'));
$app->post($pathForTransferAccomplish, function (Request $request, Response $response, array $arguments) {

    $api = new OperatorApi();
    $response = $api->accomplish($request, $response, $arguments);

    return $response;

})->setName(OperatorPage::ACTION_TRANSFER_ACCOMPLISH);

$pathForTransferAnnul = OperatorPage::ROOT . implode(IRoute::PATH_SYMBOL,
        array(OperatorPage::MODULE_TRANSFER,
            OperatorPage::ACTION_TRANSFER_ANNUL,
            '{' . OperatorPage::ID . '}'));
$app->post($pathForTransferAnnul, function (Request $request, Response $response, array $arguments) {

    $api = new OperatorApi();
    $response = $api->annul($request, $response, $arguments);

    return $response;

})->setName(OperatorPage::ACTION_TRANSFER_ANNUL);

$app->run();
