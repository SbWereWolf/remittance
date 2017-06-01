<?php

namespace Remittance\Customer;


use Remittance\Exchange\Compute;
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

    /**
     * @return bool
     * @internal param $order
     */
    public function validate(): bool
    {
        $computer = new Compute($this->dealSource, $this->dealTarget, $this->dealIncome);
        $outcome = $computer->precomputation();
        $isValid = $outcome == $this->dealOutcome;

        return $isValid;
    }

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
            $this->dealSource = $transfer->incomeCurrency;
            $this->dealTarget = $transfer->outcomeCurrency;
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
