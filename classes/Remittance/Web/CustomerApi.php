<?php

namespace Remittance\Web;


use Remittance\Customer\Order;use Slim\Http\Request;
use Slim\Http\Response;

class CustomerApi
{

    public function add(Request $request, Response $response, array $arguments)
    {

        $parsedBody = $request->getParsedBody();

        $isExistsIncome = array_key_exists('deal_income', $parsedBody);
        $dealIncome = '';
        if ($isExistsIncome) {
            $dealIncome = $parsedBody['deal_income'];
            $dealIncome = filter_var ( $dealIncome , FILTER_VALIDATE_FLOAT);
        }
        $isExistsOutcome = array_key_exists('deal_outcome', $parsedBody);
        $dealOutcome = '';
        if ($isExistsOutcome) {
            $dealOutcome = $parsedBody['deal_outcome'];
            $dealOutcome = filter_var ( $dealOutcome , FILTER_VALIDATE_FLOAT);
        }
        $isExistsEmail = array_key_exists('deal_email', $parsedBody);
        $dealEmail = '';
        if ($isExistsEmail) {
            $dealEmail = $parsedBody['deal_email'];
            $dealEmail = filter_var ( $dealEmail , FILTER_VALIDATE_EMAIL);
        }
        $isExistsSource = array_key_exists('deal_source', $parsedBody);
        $dealSource = '';
        if ($isExistsSource) {
            $dealSource = $parsedBody['deal_source'];
            $dealSource = filter_var ( $dealSource , FILTER_SANITIZE_SPECIAL_CHARS);
        }
        $isExistsTarget = array_key_exists('deal_target', $parsedBody);
        $dealTarget = '';
        if ($isExistsTarget) {
            $dealTarget = $parsedBody['deal_target'];
            $dealTarget = filter_var ( $dealTarget , FILTER_SANITIZE_SPECIAL_CHARS);
        }
        $isExistsFioTransfer = array_key_exists('fio_transfer', $parsedBody);
        $fioTransfer = '';
        if ($isExistsFioTransfer) {
            $fioTransfer = $parsedBody['fio_transfer'];
            $fioTransfer = filter_var ( $fioTransfer , FILTER_SANITIZE_SPECIAL_CHARS);
        }
        $isExistsAccountTransfer = array_key_exists('account_transfer', $parsedBody);
        $accountTransfer = '';
        if ($isExistsAccountTransfer) {
            $accountTransfer = $parsedBody['account_transfer'];
            $accountTransfer = filter_var ( $accountTransfer , FILTER_SANITIZE_SPECIAL_CHARS);
        }
        $isExistsFioReceive = array_key_exists('fio_receive', $parsedBody);
        $fioReceive = '';
        if ($isExistsFioReceive) {
            $fioReceive = $parsedBody['fio_receive'];
            $fioReceive = filter_var ( $fioReceive , FILTER_SANITIZE_SPECIAL_CHARS);
        }
        $isExistsAccountReceive = array_key_exists('account_receive', $parsedBody);
        $accountReceive = '';
        if ($isExistsAccountReceive) {
            $accountReceive = $parsedBody['account_receive'];
            $accountReceive = filter_var ( $accountReceive , FILTER_SANITIZE_SPECIAL_CHARS);
        }

        $order = new Order();

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
    }
}
