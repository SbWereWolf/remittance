<?php

namespace Remittance\Web;


use Remittance\Manager\Currency;
use Remittance\UserInput\InputArray;
use Slim\Http\Request;
use Slim\Http\Response;

class ManagerApi
{

    const ID = 'id';

    public function add(Request $request, Response $response, array $arguments)
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

    public function disable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success disable $id")
        );

        return $response;
    }

    public function enable(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);

        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success enable $id")
        );

        return $response;

    }

}
