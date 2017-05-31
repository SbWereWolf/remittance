<?php

use Remittance\Web\IPage;
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

$app->get(ManagerPage::ROOT, function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new ManagerPage($viewer, $router);

    $response = $page->root($request, $response, $arguments);

    return $response;
})->setName(ManagerPage::ROOT);

$pathForCurrencyModule = ManagerPage::ROOT . ManagerPage::MODULE_CURRENCY;
$app->get($pathForCurrencyModule, function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new ManagerPage($viewer, $router);

    $response = $page->currency($request, $response, $arguments);

    return $response;
})->setName(ManagerPage::MODULE_CURRENCY);

$pathForCurrencyAdd = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_CURRENCY,
            ManagerPage::ACTION_CURRENCY_ADD));
$app->post($pathForCurrencyAdd, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->currencyAdd($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_CURRENCY_ADD);

$pathForCurrencyDisable = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_CURRENCY,
            ManagerPage::ACTION_CURRENCY_DISABLE,
            '{' . ManagerPage::ID . '}'));
$app->post($pathForCurrencyDisable, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->currencyDisable($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_CURRENCY_DISABLE);

$pathForCurrencyEnable = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_CURRENCY,
            ManagerPage::ACTION_CURRENCY_ENABLE,
            '{' . ManagerPage::ID . '}'));
$app->post($pathForCurrencyEnable, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->currencyEnable($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_CURRENCY_ENABLE);

$pathForRateModule = ManagerPage::ROOT . ManagerPage::MODULE_RATE;
$app->get($pathForRateModule, function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new ManagerPage($viewer, $router);

    $response = $page->rate($request, $response, $arguments);

    return $response;
})->setName(ManagerPage::MODULE_RATE);

$pathForRateAdd = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_RATE,
            ManagerPage::ACTION_RATE_ADD));
$app->post($pathForRateAdd, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->rateAdd($request, $response, $arguments);

    return $response;

});

$pathForRateSave = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_RATE,
            ManagerPage::ACTION_RATE_SAVE));
$app->post($pathForRateSave, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->rateSave($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_RATE_SAVE);

$pathForRateDefault = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_RATE,
            ManagerPage::ACTION_RATE_DEFAULT,
            '{' . ManagerPage::ID . '}'));
$app->post($pathForRateDefault, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->rateDefault($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_RATE_DEFAULT);

$pathForRateEnable = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_RATE,
            ManagerPage::ACTION_RATE_ENABLE,
            '{' . ManagerPage::ID . '}'));
$app->post($pathForRateEnable, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->rateEnable($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_RATE_ENABLE);

$pathForRateDisable = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_RATE,
            ManagerPage::ACTION_RATE_DISABLE,
            '{' . ManagerPage::ID . '}'));
$app->post($pathForRateDisable, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->rateDisable($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_RATE_DISABLE);

$pathForVolumeModule = ManagerPage::ROOT . ManagerPage::MODULE_VOLUME;
$app->get($pathForVolumeModule, function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new ManagerPage($viewer, $router);

    $response = $page->volume($request, $response, $arguments);

    return $response;
})->setName(ManagerPage::MODULE_VOLUME);

//*--*
$pathForVolumeAdd = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_VOLUME,
            ManagerPage::ACTION_VOLUME_ADD));
$app->post($pathForVolumeAdd, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->volumeAdd($request, $response, $arguments);

    return $response;

});

$pathForVolumeSave = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_VOLUME,
            ManagerPage::ACTION_VOLUME_SAVE));
$app->post($pathForVolumeSave, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->volumeSave($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_VOLUME_SAVE);

$pathForVolumeEnable = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_VOLUME,
            ManagerPage::ACTION_VOLUME_ENABLE,
            '{' . ManagerPage::ID . '}'));
$app->post($pathForVolumeEnable, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->volumeEnable($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_VOLUME_ENABLE);

$pathForVolumeDisable = ManagerPage::ROOT . implode(IPage::PATH_SYMBOL,
        array(ManagerPage::MODULE_VOLUME,
            ManagerPage::ACTION_VOLUME_DISABLE,
            '{' . ManagerPage::ID . '}'));
$app->post($pathForVolumeDisable, function (Request $request, Response $response, array $arguments) {

    $api = new \Remittance\Web\ManagerApi();
    $response = $api->volumeDisable($request, $response, $arguments);

    return $response;

})->setName(ManagerPage::ACTION_VOLUME_DISABLE);

//*--*

$pathForSettingModule = ManagerPage::ROOT . ManagerPage::MODULE_SETTING;
$app->get($pathForSettingModule, function (Request $request, Response $response, array $arguments) {

    $router = $this->get(ROUTER_COMPONENT);
    $viewer = $this->get(VIEWER_COMPONENT);
    $page = new ManagerPage($viewer, $router);

    $response = $page->root($request, $response, $arguments);

    return $response;
})->setName(ManagerPage::MODULE_SETTING);

$app->run();
