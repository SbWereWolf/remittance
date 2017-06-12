<?php

namespace Remittance\BusinessLogic\Customer;


use Remittance\BusinessLogic\Exchange\Deal;
use Remittance\BusinessLogic\Manager\Volume;
use Remittance\BusinessLogic\Operator\Transfer;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;

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
     */
    public function validate(): bool
    {
        $computer = new Deal($this->dealSource, $this->dealTarget, $this->dealIncome);
        $computer->precomputation();
        $isValid = $computer->outcome == $this->dealOutcome;

        if ($isValid) {
            $target = new Volume();
            $isValid = $target->testOutcome($this->dealTarget, $this->dealOutcome);
        }

        return $isValid;
    }

    public function place():string
    {

        $transfer = new Transfer();

        $isSuccess = $transfer->add($this);

        $currency = new CurrencyRecord();
        if ($isSuccess) {
            $currencySearcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
            $currency = $currencySearcher->searchByCode($transfer->incomeCurrency);

            $isSuccess = !empty($currency->id);
        }

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

            $result = 'Ожидается поступление'
                . " $transfer->dealIncome на счёт $transfer->accountAwait $transfer->fioAwait ($currency->title)"
                . ", добавлен заказ № $transfer->documentNumber от $transfer->documentDate"
                . ", адрес для уведомлений : $this->dealEmail;";
        }
        if (!$isSuccess) {
            $result = "Ошибка добавления заказа";
        }


        return $result;
    }

}
