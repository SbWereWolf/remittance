<?php

/*
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
*/

use Slim\Http\Request;
use Slim\Http\Response;

define('APPLICATION_ROOT', realpath(__DIR__));
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

    $response = $this->view->render($response, "remittance/remittance.php", []);

    return $response;
});

$app->post('/order/add', function (Request $request, Response $response, array $arguments) {

    $parsedBody = $request->getParsedBody();

    $isExistsIncome = array_key_exists('deal_income', $parsedBody);
    $dealIncome = '';
    if ($isExistsIncome) {
        $dealIncome = $parsedBody['deal_income'];
    }
    $isExistsOutcome = array_key_exists('deal_outcome', $parsedBody);
    $dealOutcome = '';
    if ($isExistsOutcome) {
        $dealOutcome = $parsedBody['deal_outcome'];
    }
    $isExistsEmail = array_key_exists('deal_email', $parsedBody);
    $dealEmail = '';
    if ($isExistsEmail) {
        $dealEmail = $parsedBody['deal_email'];
    }
    $isExistsSource = array_key_exists('deal_source', $parsedBody);
    $dealSource = '';
    if ($isExistsSource) {
        $dealSource = $parsedBody['deal_source'];
    }
    $isExistsTarget = array_key_exists('deal_target', $parsedBody);
    $dealTarget = '';
    if ($isExistsTarget) {
        $dealTarget = $parsedBody['deal_target'];
    }
    $isExistsFioTransfer = array_key_exists('fio_transfer', $parsedBody);
    $fioTransfer = '';
    if ($isExistsFioTransfer) {
        $fioTransfer = $parsedBody['fio_transfer'];
    }
    $isExistsAccountTransfer = array_key_exists('account_transfer', $parsedBody);
    $accountTransfer = '';
    if ($isExistsAccountTransfer) {
        $accountTransfer = $parsedBody['account_transfer'];
    }
    $isExistsFioReceive = array_key_exists('fio_receive', $parsedBody);
    $fioReceive = '';
    if ($isExistsFioReceive) {
        $fioReceive = $parsedBody['fio_receive'];
    }
    $isExistsAccountReceive = array_key_exists('account_receive', $parsedBody);
    $accountReceive = '';
    if ($isExistsAccountReceive) {
        $accountReceive = $parsedBody['account_receive'];
    }

    $order = new \Remittance\Customer\Order();

    $order->dealEmail = $dealEmail;
    $order->fioReceive = $fioReceive;
    $order->accountReceive = $accountReceive;
    $order->accountTransfer = $accountTransfer;
    $order->dealIncome = $dealIncome;
    $order->dealOutcome = $dealOutcome;
    $order->dealSource = $dealSource;
    $order->dealTarget = $dealTarget;
    $order->fioTransfer = $fioTransfer;

    $placementMessage = $order->place();

    $result = var_export($placementMessage, true);


    $response = $response->withJson(
        array('result' => $result)
    );

    return $response;
});

$app->run();
