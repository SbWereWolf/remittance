<?php

namespace Remittance\Web;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\Manager\Currency;
use Remittance\Manager\Rate;
use Remittance\Manager\Volume;
use Remittance\UserInput\InputArray;
use Slim\Http\Request;
use Slim\Http\Response;

class ManagerApi implements IApi
{

    const ID = 'id';

    public function currencyAdd(Request $request, Response $response, array $arguments)
    {

        $parsedBody = $request->getParsedBody();
        $inputArray = new InputArray($parsedBody);

        $code = $inputArray->getSpecialCharsValue('code');
        $title = $inputArray->getSpecialCharsValue('title');
        $description = $inputArray->getSpecialCharsValue('description');
        $disable = $inputArray->getBooleanValue('disable');

        $currency = new Currency();

        $currency->code = $code;
        $currency->title = $title;
        $currency->description = $description;
        $currency->isDisable = $disable;

        $addMessage = $currency->add();

        $result = var_export($addMessage, true);


        $response = $response->withJson(
            array('result' => $result)
        );

        return $response;
    }

    public function currencySave(Request $request, Response $response, array $arguments)
    {
        $inputArray = InputArray::getFormData($request);

        $code = $inputArray->getSpecialCharsValue('code');
        $title = $inputArray->getSpecialCharsValue('title');
        $description = $inputArray->getSpecialCharsValue('description');
        $disable = $inputArray->getBooleanValue('disable');

        $currency = new Currency();

        $currency->code = $code;
        $currency->title = $title;
        $currency->description = $description;
        $currency->isDisable = $disable;

        $message = $currency->store();

        $response = $response->withJson(
            array('result' => $message)
        );

        return $response;

    }

    public function currencyDisable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);
        $id = $inputArray->getIntegerValue(self::ID);

        $currency = new Currency();
        $isSuccess = $currency->assembleCurrency($id);

        $disableResult = false;
        if ($isSuccess) {
            $disableResult = $currency->disable();
        }

        $resultMessage = $disableResult ? 'success' : 'fail';
        $response = $response->withJson(
            array('message' => "$resultMessage disable $id")
        );

        return $response;
    }

    public function currencyEnable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);
        $id = $inputArray->getIntegerValue(self::ID);

        $currency = new Currency();
        $isSuccess = $currency->assembleCurrency($id);

        $enableResult = false;
        if ($isSuccess) {
            $enableResult = $currency->enable();
        }

        $resultMessage = $enableResult ? 'success' : 'fail';
        $response = $response->withJson(
            array('message' => "$resultMessage enable $id")
        );

        return $response;
    }

    public function rateAdd(Request $request, Response $response, array $arguments)
    {
        $parsedBody = $request->getParsedBody();
        $formData = Common::setIfExists(self::FORM_DATA, $parsedBody, ICommon::EMPTY_VALUE);

        $isValid = !empty($formData);
        $rateData = ICommon::EMPTY_ARRAY;
        if ($isValid) {
            parse_str($formData, $rateData);
        }

        $inputArray = new InputArray($rateData);

        $sourceCurrency = $inputArray->getSpecialCharsValue('source_currency');
        $targetCurrency = $inputArray->getSpecialCharsValue('target_currency');
        $exchangeRate = $inputArray->getFloatValue('rate');
        $fee = $inputArray->getFloatValue('fee');
        $default = $inputArray->getBooleanValue('default');
        $disable = $inputArray->getBooleanValue('disable');

        $rate = new Rate();

        $rate->sourceCurrency = $sourceCurrency;
        $rate->targetCurrency = $targetCurrency;
        $rate->ratio = $exchangeRate;
        $rate->fee = $fee;
        $rate->isDefault = $default;
        $rate->isDisable = $disable;

        $message = $rate->add();

        $response = $response->withJson(
            array('result' => $message)
        );

        return $response;

    }

    public function rateSave(Request $request, Response $response, array $arguments)
    {
        $inputArray = InputArray::getFormData($request);

        $ratio = $inputArray->getFloatValue('ratio');
        $fee = $inputArray->getFloatValue('fee');
        $sourceCurrency = $inputArray->getSpecialCharsValue('source_currency');
        $targetCurrency = $inputArray->getSpecialCharsValue('target_currency');
        $disable = $inputArray->getBooleanValue('disable');
        $default = $inputArray->getBooleanValue('default');

        $rate = new Rate();

        $rate->ratio = $ratio;
        $rate->fee = $fee;
        $rate->sourceCurrency = $sourceCurrency;
        $rate->targetCurrency = $targetCurrency;
        $rate->isDisable = $disable;
        $rate->isDefault = $default;

        $message = $rate->store();

        $response = $response->withJson(
            array('result' => $message)
        );

        return $response;

    }

    public function rateDefault(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $rate = new Rate();
        $isSuccess = $rate->assembleRate($id);

        $setAsDefault = false;
        if ($isSuccess) {
            $setAsDefault = $rate->setAsDefault();
        }

        $resultMessage = $setAsDefault ? 'success' : 'fail';
        $response = $response->withJson(
            array('message' => "$resultMessage set as default $id")
        );

        return $response;

    }

    public function rateEnable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $rate = new Rate();
        $isSuccess = $rate->assembleRate($id);

        $setEnable = false;
        if ($isSuccess) {
            $setEnable = $rate->enable();
        }

        $resultMessage = $setEnable ? 'success' : 'fail';
        $response = $response->withJson(
            array('message' => "$resultMessage enable $id")
        );

        return $response;
    }

    public function rateDisable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $rate = new Rate();
        $isSuccess = $rate->assembleRate($id);

        $setDisable = false;
        if ($isSuccess) {
            $setDisable = $rate->disable();
        }

        $resultMessage = $setDisable ? 'success' : 'fail';
        $response = $response->withJson(
            array('message' => "$resultMessage disable $id")
        );

        return $response;

    }

    public function volumeAdd(Request $request, Response $response, array $arguments)
    {
        $inputArray = InputArray::getFormData($request);

        $currency = $inputArray->getSpecialCharsValue('currency');
        $amount = $inputArray->getFloatValue('amount');
        $reserve = $inputArray->getFloatValue('reserve');
        $accountName = $inputArray->getSpecialCharsValue('account_name');
        $accountNumber = $inputArray->getSpecialCharsValue('account_number');
        $limitation = $inputArray->getFloatValue('limitation');
        $total = $inputArray->getFloatValue('total');
        $disable = $inputArray->getBooleanValue('disable');

        $volume = new Volume();

        $volume->currency = $currency;
        $volume->amount = $amount;
        $volume->reserve = $reserve;
        $volume->accountName = $accountName;
        $volume->accountNumber = $accountNumber;
        $volume->limitation = $limitation;
        $volume->total = $total;
        $volume->isDisable = $disable;

        $message = $volume->add();

        $response = $response->withJson(
            array('result' => $message)
        );

        return $response;

    }

    public function volumeSave(Request $request, Response $response, array $arguments)
    {
        $inputArray = InputArray::getFormData($request);

        $currency = $inputArray->getSpecialCharsValue('currency');
        $amount = $inputArray->getFloatValue('amount');
        $reserve = $inputArray->getFloatValue('reserve');
        $accountName = $inputArray->getSpecialCharsValue('account_name');
        $accountNumber = $inputArray->getSpecialCharsValue('account_number');
        $limitation = $inputArray->getFloatValue('limitation');
        $total = $inputArray->getFloatValue('total');
        $disable = $inputArray->getBooleanValue('disable');

        $volume = new Volume();

        $volume->currency = $currency;
        $volume->amount = $amount;
        $volume->reserve = $reserve;
        $volume->accountName = $accountName;
        $volume->accountNumber = $accountNumber;
        $volume->limitation = $limitation;
        $volume->total = $total;
        $volume->isDisable = $disable;

        $message = $volume->store();

        $response = $response->withJson(
            array('result' => $message)
        );

        return $response;

    }

    public function volumeEnable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $volume = new Volume();
        $isSuccess = $volume->assembleVolume($id);

        $setEnable = false;
        if ($isSuccess) {
            $setEnable = $volume->enable();
        }

        $resultMessage = $setEnable ? 'success' : 'fail';
        $response = $response->withJson(
            array('message' => "$resultMessage enable $id")
        );

        return $response;
    }

    public function volumeDisable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $volume = new Volume();
        $isSuccess = $volume->assembleVolume($id);

        $setDisable = false;
        if ($isSuccess) {
            $setDisable = $volume->disable();
        }

        $resultMessage = $setDisable ? 'success' : 'fail';
        $response = $response->withJson(
            array('message' => "$resultMessage disable $id")
        );

        return $response;

    }
}
