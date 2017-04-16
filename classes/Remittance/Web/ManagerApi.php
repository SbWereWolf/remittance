<?php

namespace Remittance\Web;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\Manager\Currency;
use Remittance\Manager\Rate;
use Remittance\UserInput\InputArray;
use Slim\Http\Request;
use Slim\Http\Response;

class ManagerApi
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
        $currency->disable = $disable;

        $addMessage = $currency->add();

        $result = var_export($addMessage, true);


        $response = $response->withJson(
            array('result' => $result)
        );

        return $response;
    }

    public function currencyDisable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success disable $id")
        );

        return $response;
    }

    public function currencyEnable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success enable $id")
        );

        return $response;

    }

    public function rateAdd(Request $request, Response $response, array $arguments)
    {
        $parsedBody = $request->getParsedBody();
        $formData = Common::setIfExists('form_data', $parsedBody, ICommon::EMPTY_VALUE);

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
        $rate->rate = $exchangeRate;
        $rate->fee = $fee;
        $rate->default = $default;
        $rate->disable = $disable;

        $message = $rate->add();

        $response = $response->withJson(
            array('result' => $message)
        );

        return $response;

    }

    public function rateSave(Request $request, Response $response, array $arguments)
    {
        $parsedBody = $request->getParsedBody();
        $inputArray = new InputArray($parsedBody);

        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success enable $id")
        );

        return $response;

    }

    public function rateDefault(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success enable $id")
        );

        return $response;

    }

    public function rateEnable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success enable $id")
        );

        return $response;

    }

    public function rateDisable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success enable $id")
        );

        return $response;

    }


}
