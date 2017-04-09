<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 05.04.2017
 * Time: 2:05
 */

namespace Remittance\Customer;


use Remittance\Operator\Transfer;

class Order
{
    public $dealIncome = 0;
    public $dealOutcome = 0;
    public $dealSource = '';
    public $dealTarget = '';
    public $dealEmail = '';
    public $fioTransfer = '';
    public $accountTransfer = '';
    public $fioReceive = '';
    public $accountReceive = '';

    public function place():string
    {

        $transfer = new Transfer();

        $isSuccess = $transfer->add($this);

        $result = '';
        if ($isSuccess) {

            $this->accountReceive = $transfer->accountReceive;
            $this->accountTransfer = $transfer->accountTransfer;
            $this->dealEmail = $transfer->dealEmail;
            $this->dealIncome = $transfer->dealIncome;
            $this->dealOutcome = $transfer->dealOutcome;
            $this->dealSource = $transfer->incomeAccount;
            $this->dealTarget = $transfer->outcomeAccount;
            $this->fioReceive = $transfer->fioReceive;
            $this->fioTransfer = $transfer->fioTransfer;

            $result = "Добавлен заказ № $transfer->documentNumber от $transfer->documentDate"
                . ", адрес для уведомлений : $this->dealEmail;";
        }
        if (!$isSuccess) {
            $result = "Ошибка добавления заказа";
        }


        return $result;
    }

}
