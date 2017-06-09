<?php

namespace Remittance\Presentation\Web;

use Remittance\BusinessLogic\Operator\Transfer;
use Remittance\Presentation\UserInput\InputArray;
use Slim\Http\Request;
use Slim\Http\Response;

class OperatorApi
{

    const ID = 'id';

    public function accomplish(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);
        $id = $inputArray->getIntegerValue(self::ID);

        $transfer = new Transfer();
        $isSuccess = $transfer->assembleTransfer($id);

        $accomplishResult = false;
        if ($isSuccess) {
            $accomplishResult = $transfer->accomplish();
        }

        $resultMessage = $accomplishResult ? 'success' : 'fail';

        $response = $response->withJson(
            array('message' => "$resultMessage accomplish $id")
        );

        return $response;
    }

    public function annul(Request $request, Response $response, array $arguments)
    {
        $inputArray = new InputArray($arguments);
        $id = $inputArray->getIntegerValue(self::ID);

        $transfer = new Transfer();
        $isSuccess = $transfer->assembleTransfer($id);

        $annulResult = false;
        if ($isSuccess) {
            $annulResult = $transfer->annul();
        }

        $resultMessage = $annulResult ? 'success' : 'fail';

        $response = $response->withJson(
            array('message' => "$resultMessage annul $id")
        );

        return $response;
    }

}
