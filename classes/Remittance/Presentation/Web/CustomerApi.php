<?php

namespace Remittance\Presentation\Web;


use Remittance\BusinessLogic\Customer\Order;
use Remittance\BusinessLogic\Exchange\Deal;
use Remittance\Presentation\UserInput\InputArray;
use Remittance\Presentation\UserOutput\JsonFloat;
use Slim\Http\Request;
use Slim\Http\Response;

class CustomerApi
{

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

        $isValid = $order->validate();

        $result = 'fail';
        if (!$isValid) {
            $result = 'error. please refresh data-page ("F5")';
        }

        if ($isValid) {

            $placementMessage = $order->placement();

            $result = var_export($placementMessage, true);
        }

        $response = $response->withJson(
            array('result' => $result)
        );

        return $response;
    }

    public function compute(Request $request, Response $response, array $arguments): Response
    {

        $parsedBody = $request->getParsedBody();
        $inputArray = new InputArray($parsedBody);

        $dealSource = $inputArray->getSpecialCharsValue(self::DEAL_SOURCE);
        $dealTarget = $inputArray->getSpecialCharsValue(self::DEAL_TARGET);
        $dealIncome = $inputArray->getFloatValue(self::DEAL_INCOME);

        $deal = new Deal($dealSource, $dealTarget, $dealIncome);
        $deal->precomputation();

        $dealOutcome = $deal->outcome;

        $effectiveRatio = $dealOutcome / $dealIncome;
        $source = 1;
        $target = $effectiveRatio;

        $isLess = $effectiveRatio < 1;
        if ($isLess) {
            $effectiveRatio = $dealIncome / $dealOutcome;

            $source = $effectiveRatio;
            $target = 1;
        }

        $sourceJson = new JsonFloat($source);
        $sourceJson->prepare();
        $targetJson = new JsonFloat($target);
        $targetJson->prepare();
        $outcome = new JsonFloat($dealOutcome);
        $outcome->prepare();

        $response = $response->withJson(
            array('outcome' => $outcome->value,
                'income_currency' => $dealSource,
                'income_amount' => $sourceJson->value,
                'outcome_currency' => $dealTarget,
                'outcome_amount' => $targetJson->value,)
        );

        return $response;
    }

}
