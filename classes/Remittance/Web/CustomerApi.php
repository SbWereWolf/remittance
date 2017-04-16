<?php

namespace Remittance\Web;


use Remittance\Customer\Order;
use Remittance\Exchange\Compute;
use Remittance\UserInput\InputArray;
use Slim\Http\Request;
use Slim\Http\Response;

class CustomerApi
{
    const MODULE_COMPUTE = 'compute';

    const DEAL_EMAIL = 'deal_email';
    const FIO_RECEIVE = 'fio_receive';
    const ACCOUNT_RECEIVE = 'account_receive';
    const FIO_TRANSFER = 'fio_transfer';
    const ACCOUNT_TRANSFER = 'account_transfer';
    const DEAL_SOURCE = 'deal_source';
    const DEAL_INCOME = 'deal_income';
    const DEAL_TARGET = 'deal_target';
    const DEAL_OUTCOME = 'deal_outcome';

    public function add(Request $request, Response $response, array $arguments)
    {

        $parsedBody = $request->getParsedBody();
        $inputArray = new InputArray($parsedBody);

        $dealEmail = $inputArray->getSpecialCharsValue(self::DEAL_EMAIL);
        $fioReceive = $inputArray->getSpecialCharsValue(self::FIO_RECEIVE);
        $accountReceive = $inputArray->getSpecialCharsValue(self::ACCOUNT_RECEIVE);
        $fioTransfer = $inputArray->getSpecialCharsValue(self::FIO_TRANSFER);
        $accountTransfer = $inputArray->getSpecialCharsValue(self::ACCOUNT_TRANSFER);
        $dealSource = $inputArray->getSpecialCharsValue(self::DEAL_SOURCE);
        $dealIncome = $inputArray->getFloatValue(self::DEAL_INCOME);
        $dealTarget = $inputArray->getSpecialCharsValue(self::DEAL_TARGET);
        $dealOutcome = $inputArray->getFloatValue(self::DEAL_OUTCOME);

        $order = new Order();

        $order->dealEmail = $dealEmail;
        $order->fioReceive = $fioReceive;
        $order->accountReceive = $accountReceive;
        $order->fioTransfer = $fioTransfer;
        $order->accountTransfer = $accountTransfer;
        $order->dealSource = $dealSource;
        $order->dealIncome = $dealIncome;
        $order->dealTarget = $dealTarget;
        $order->dealOutcome = $dealOutcome;

        $placementMessage = $order->place();

        $result = var_export($placementMessage, true);


        $response = $response->withJson(
            array('result' => $result)
        );

        return $response;
    }

    public function compute(Request $request, Response $response, array $arguments)
    {

        $parsedBody = $request->getParsedBody();
        $inputArray = new InputArray($parsedBody);

        $dealSource = $inputArray->getSpecialCharsValue(self::DEAL_SOURCE);
        $dealTarget = $inputArray->getSpecialCharsValue(self::DEAL_TARGET);
        $dealIncome = $inputArray->getFloatValue(self::DEAL_INCOME);

        $computer = new Compute($dealSource, $dealTarget, $dealIncome);
        $outcome = $computer->precomputation();

        $result = var_export($outcome, true);
        $response = $response->withJson(
            array('result' => $result)
        );

        return $response;
    }
}
