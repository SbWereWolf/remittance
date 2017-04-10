<?php

namespace Remittance\Web;

use Remittance\Core\Common;
use Slim\Http\Request;
use Slim\Http\Response;

class OperatorApi
{

    const ID = 'id';

    public function accomplish(Request $request, Response $response, array $arguments)
    {

        $id = Common::setIfExists(self::ID, $arguments, Common::EMPTY_VALUE);
        $response = $response->withJson(
            array('message' => "success accomplish $id")
        );

        return $response;
    }

    public function annul(Request $request, Response $response, array $arguments)
    {

        $id = Common::setIfExists(self::ID, $arguments, Common::EMPTY_VALUE);
        $response = $response->withJson(
            array('message' => "success annul $id")
        );

        return $response;

    }

}
