<?php

namespace Remittance\Manager;


use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\NamedEntity;
use Remittance\DataAccess\Entity\RateRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Remittance\DataAccess\Search\RateSearch;
use const false;
use Remittance\Exchange\Compute;

class Rate
{
    /** @var string значение для поднятого флага "отключен" */
    const DEFINE_AS_DISABLE = true;
    /** @var string значение для снятого флага "отключен" */
    const DEFINE_AS_ENABLE = false;
    /** @var string значение по умолчанию для признака "отключен" */
    const DEFAULT_IS_DISABLE = self::DEFINE_AS_ENABLE;
    /** @var string значение для поднятого флага "использовать по умолчанию" */
    const DEFINE_AS_DEFAULT = true;
    /** @var string значение для снятого флага "использовать по умолчанию" */
    const DEFINE_AS_NOT_DEFAULT = false;
    /** @var string значение по умолчанию для флага "использовать по умолчанию" */
    const DEFAULT_IS_DEFAULT = self::DEFINE_AS_NOT_DEFAULT;

    public $sourceCurrency = '';
    public $targetCurrency = '';
    public $rate = 0;
    public $fee = 0;
    public $isDefault = self::DEFINE_AS_NOT_DEFAULT;
    public $isDisable = self::DEFAULT_IS_DISABLE;


    public function add(): string
    {

        $record = new RateRecord();
        $isSuccess = $record->addEntity();

        $isSourceDefined = false;
        $isTargetDefined = false;
        $sourceCurrency = new NamedEntity();
        $targetCurrency = new NamedEntity();
        if ($isSuccess) {

            $record->isHidden = $this->isDisable;
            $record->effectiveRate = (1 - $this->fee) * $this->rate;
            $record->exchangeRate = $this->rate;
            $record->fee = $this->fee;
            $record->isDefault = $this->isDefault;

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

            $this->isDisable = $record->isHidden;
            $this->rate = $record->exchangeRate;
            $this->fee = $record->fee;
            $this->isDefault = $record->isDefault;

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

    public function assembleRate(int $id): bool
    {

        $searcher = new RateSearch();
        $foundRecord = $searcher->searchById($id);

        $isSourceFound = false;
        $isTargetFound = false;
        $source = new NamedEntity();
        $target = new NamedEntity();
        $isSuccess = !empty($foundRecord->id);
        if ($isSuccess) {
            $this->isDisable = $foundRecord->isHidden;
            $this->rate = $foundRecord->exchangeRate;
            $this->fee = $foundRecord->fee;
            $this->isDefault = $foundRecord->isDefault;

            $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);

            $source = $searcher->searchById($foundRecord->sourceCurrencyId);
            $isSourceFound = !empty($source->id);

            $target = $searcher->searchById($foundRecord->targetCurrencyId);
            $isTargetFound = !empty($target->id);
        }

        $isSuccess = $isSourceFound && $isTargetFound;
        if ($isSuccess) {

            $this->sourceCurrency = $source->code;
            $this->targetCurrency = $target->code;
        }

        return $isSuccess;
    }

    public function setAsDefault():bool
    {
        $this->isDefault = self::DEFINE_AS_DEFAULT;
        $result = $this->setDefault();

        return $result;
    }

    private function save():bool
    {
        $record = $this->assembleRecord();

        $isValid = !empty($record->targetCurrencyId) && !empty($record->sourceCurrencyId);
        $result = false;
        if($isValid){
            $result = $record->save();
        }

        if ($result) {
            $this->assumeRecord($record);
        }

        return $result;
    }

    private function assembleRecord():RateRecord
    {
        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);

        $sourceCurrency = $searcher->searchByCode($this->sourceCurrency);
        $isSourceDefined = !empty($sourceCurrency->id);

        $targetCurrency = $searcher->searchByCode($this->targetCurrency);
        $isTargetDefined = !empty($targetCurrency->id);

        $record = new RateRecord();
        if ($isSourceDefined) {
            $record->sourceCurrencyId = $sourceCurrency->id;
        }
        if ($isTargetDefined) {
            $record->targetCurrencyId = $targetCurrency->id;
        }

        $isValid = $isSourceDefined && $isTargetDefined;
        if($isValid){

            $computer = new Compute('','',1);
            $record->effectiveRate = $computer->calculate($this->fee,$this->rate) ;

            $record->isHidden = $this->isDisable;
            $record->exchangeRate = $this->rate;
            $record->fee = $this->fee;
            $record->isDefault = $this->isDefault;
        }

        return $record;
    }

    private function assumeRecord(RateRecord $record)
    {

        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);

        $source = $searcher->searchById($record->sourceCurrencyId);
        $isSourceFound = !empty($source->id);

        $target = $searcher->searchById($record->targetCurrencyId);
        $isTargetFound = !empty($target->id);

        $isSuccess = $isSourceFound && $isTargetFound;
        if ($isSuccess) {

            $this->sourceCurrency = $source->code;
            $this->targetCurrency = $target->code;

            $this->isDisable = $record->isHidden;
            $this->rate = $record->exchangeRate;
            $this->fee = $record->fee;
            $this->isDefault = $record->isDefault;
        }

        return $isSuccess;

    }

    public function enable():bool
    {
        $this->isDisable = self::DEFINE_AS_ENABLE;
        $result = $this->save();

        return $result;
    }

    public function disable():bool
    {
        $this->isDisable = self::DEFINE_AS_DISABLE;
        $result = $this->save();

        return $result;
    }

    private function setDefault():bool
    {
        $record = $this->assembleRecord();

        $isValid = !empty($record->targetCurrencyId) && !empty($record->sourceCurrencyId);
        $result = false;
        if($isValid){
            $dummy = $record->unsetDefault();
            $result = $record->setDefault();
        }

        if ($result) {
            $this->assumeRecord($record);
        }

        return $result;
    }
}
