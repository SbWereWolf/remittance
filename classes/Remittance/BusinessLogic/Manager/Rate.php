<?php

namespace Remittance\BusinessLogic\Manager;


use Remittance\BusinessLogic\Property\IDefault;
use Remittance\BusinessLogic\Property\IDisable;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\NamedEntity;
use Remittance\DataAccess\Entity\RateRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Remittance\DataAccess\Search\RateSearch;

class Rate implements IDefault, IDisable
{

    public $sourceCurrency = '';
    public $targetCurrency = '';
    public $ratio = 0;
    public $fee = 0;
    public $isDisable = self::DEFAULT_IS_DISABLE;
    public $isDefault = self::DEFAULT_IS_DEFAULT;
    public $description = '';


    public function add(): string
    {

        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);

        $sourceCurrency = $searcher->searchByCode($this->sourceCurrency);
        $isSourceDefined = !empty($sourceCurrency->id);

        $targetCurrency = $searcher->searchByCode($this->targetCurrency);
        $isTargetDefined = !empty($targetCurrency->id);

        $record = new RateRecord();
        $isSuccess = false;
        $isValid = $isSourceDefined && $isTargetDefined;
        if ($isValid) {
            $isSuccess = $record->addEntity();
        }

        if ($isSourceDefined) {
            $record->sourceCurrencyId = $sourceCurrency->id;
        }
        if ($isTargetDefined) {
            $record->targetCurrencyId = $targetCurrency->id;
        }

        $isValid = $isSourceDefined && $isTargetDefined;
        if ($isValid) {

            $record->isHidden = $this->isDisable;
            $record->ratio = $this->ratio;
            $record->fee = $this->fee;
            $record->isDefault = $this->isDefault;
            $record->description = $this->description;

            $isSuccess = $record->mutateEntity();
        }

        $isSourceFound = false;
        $isTargetFound = false;
        $source = new NamedEntity();
        $target = new NamedEntity();
        if ($isSuccess) {

            $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);

            $source = $searcher->searchById($record->sourceCurrencyId);
            $isSourceFound = !empty($source->id);

            $target = $searcher->searchById($record->targetCurrencyId);
            $isTargetFound = !empty($target->id);
        }

        $result = ICommon::EMPTY_VALUE;
        $isSuccess = $isSourceFound && $isTargetFound;
        if ($isSuccess) {

            $this->sourceCurrency = $source->code;
            $this->targetCurrency = $target->code;

            $this->isDisable = $record->isHidden;
            $this->ratio = $record->ratio;
            $this->fee = $record->fee;
            $this->isDefault = $record->isDefault;
            $this->description = $record->description;

            $result = "Добавлена ставка $record->ratio ( $record->fee ) для обмена $this->sourceCurrency на $this->targetCurrency";
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
            $this->ratio = $foundRecord->ratio;
            $this->fee = $foundRecord->fee;
            $this->isDefault = $foundRecord->isDefault;
            $this->description = $foundRecord->description;

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

            $record->isHidden = $this->isDisable;
            $record->ratio = $this->ratio;
            $record->fee = $this->fee;
            $record->isDefault = $this->isDefault;
            $record->description = $this->description;
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
            $this->ratio = $record->ratio;
            $this->fee = $record->fee;
            $this->isDefault = $record->isDefault;
            $this->description = $record->description;
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

    public function store(): string
    {
        $isSuccess = $this->save();

        $result = $isSuccess ? 'Изменения сохранены' : 'Ошибка сохранения';

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
