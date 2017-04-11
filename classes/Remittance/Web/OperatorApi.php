<?php

namespace Remittance\Web;

use Remittance\Core\Common;
use Remittance\UserInput\InputArray;
use Slim\Http\Request;
use Slim\Http\Response;

class OperatorApi
{

    const ID = 'id';

    public function accomplish(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);
        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success accomplish $id")
        );

        return $response;
    }

    public function annul(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);
        $id = $inputArray->getIntegerValue(self::ID);

        $response = $response->withJson(
            array('message' => "success annul $id")
        );

        return $response;

    }

}
