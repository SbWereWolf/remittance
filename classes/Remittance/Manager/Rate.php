<?php

namespace Remittance\Manager;


use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\NamedEntity;
use Remittance\DataAccess\Entity\RateRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use const false;

class Rate
{
    public $sourceCurrency;
    public $targetCurrency;
    public $rate;
    public $fee;
    public $default;
    public $disable;


    public function add(): string
    {

        $record = new RateRecord();

        $isSuccess = $record->addEntity();
        $isSourceDefined = false;
        $isTargetDefined = false;
        $sourceCurrency = new NamedEntity();
        $targetCurrency = new NamedEntity();
        if ($isSuccess) {

            $record->isHidden = $this->disable;
            $record->effectiveRate = (1 - $this->fee) * $this->rate;
            $record->exchangeRate = $this->rate;
            $record->fee = $this->fee;
            $record->isDefault = $this->default;

            $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);

            $sourceCurrency = $searcher->searchByCode($this->sourceCurrency);
            $isSourceDefined = !empty($sourceCurrency->id);

            $targetCurrency = $searcher->searchByCode($this->targetCurrency);
            $isTargetDefined = !empty($targetCurrency->id);
        }

        if ($isSourceDefined) {
            $record->sourceCurrencyId = $sourceCurrency->id;
        }
        if ($isTargetDefined) {
            $record->targetCurrencyId = $targetCurrency->id;
        }

        $isValid = $isSourceDefined && $isTargetDefined;
        if ($isValid) {
            $isSuccess = $record->mutateEntity();
        }

        $result = ICommon::EMPTY_VALUE;
        $isSourceFound = false;
        $isTargetFound = false;
        $source = new NamedEntity();
        $target = new NamedEntity();
        if ($isSuccess) {

            $this->disable = $record->isHidden;
            $this->rate = $record->exchangeRate;
            $this->fee = $record->fee;
            $this->default = $record->isDefault;

            $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);

            $source = $searcher->searchById($record->sourceCurrencyId);
            $isSourceFound = !empty($source->id);

            $target = $searcher->searchById($record->targetCurrencyId);
            $isTargetFound = !empty($target->id);
        }

        $isSuccess = $isSourceFound && $isTargetFound;
        if ($isSuccess) {

            $this->sourceCurrency = $source->code;
            $this->targetCurrency = $target->code;

            $result = "Добавлена ставка $record->exchangeRate ( $record->fee ) для обмена $this->sourceCurrency на $this->targetCurrency";
        }
        if (!$isSuccess) {
            $result = "Ошибка добавления ставки";
        }

        return $result;
    }
}
