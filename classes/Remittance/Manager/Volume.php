<?php

namespace Remittance\Manager;


use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\NamedEntity;
use Remittance\DataAccess\Entity\VolumeRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Remittance\DataAccess\Search\VolumeSearch;

class Volume
{
    /** @var boolean значение для поднятого флага "отключен" */
    const DEFINE_AS_DISABLE = true;
    /** @var boolean значение для снятого флага "отключен" */
    const DEFINE_AS_ENABLE = false;
    /** @var boolean значение по умолчанию для флага "отключен" */
    const DEFAULT_IS_DISABLE = self::DEFINE_AS_ENABLE;

    public $currency = '';
    public $amount = 0;
    public $reserve = 0;
    public $accountName = '';
    public $accountNumber = '';
    public $limitation = 0;
    public $total = 0;
    public $isDisable = self::DEFAULT_IS_DISABLE;

    /**
     * @param $currency
     * @param $amount
     * @return bool
     */
    public function applyOutcome(string $currency, float $amount): bool
    {
        $isSuccess = $this->assembleByCurrency($currency);

        if ($isSuccess) {
            $isSuccess = $this->outcome($amount);
        }
        return $isSuccess;
    }

    /**
     * @param $currency
     * @param $amount
     * @return bool
     */
    public function applyIncome(string $currency, float $amount): bool
    {
        $isSuccess = $this->assembleByCurrency($currency);

        if ($isSuccess) {
            $isSuccess = $this->income($amount);
        }
        return $isSuccess;
    }


    public function add(): string
    {

        $currencyRecord = $this->getCurrencyRecord();
        $isCurrencyDefined = !empty($currencyRecord->id);

        $record = new VolumeRecord();
        $isSuccess = false;
        if ($isCurrencyDefined) {
            $isSuccess = $record->addEntity();
        }

        if ($isSuccess) {

            $record->isHidden = $this->isDisable;
            $record->amount = $this->amount;
            $record->reserve = $this->reserve;
            $record->accountName = $this->accountName;
            $record->accountNumber = $this->accountNumber;
            $record->limitation = $this->limitation;
            $record->total = $this->total;
            $record->currencyId = $currencyRecord->id;

            $isSuccess = $record->mutateEntity();
        }

        $currencyEntity = new NamedEntity();
        if ($isSuccess) {
            $currencySearcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
            $currencyEntity = $currencySearcher->searchById($record->currencyId);
        }
        $isCurrencyFound = !empty($currencyEntity->id);

        $result = ICommon::EMPTY_VALUE;
        $isSuccess = $isCurrencyFound;

        if ($isSuccess) {

            $this->isDisable = $record->isHidden;
            $this->amount = $record->amount;
            $this->reserve = $record->reserve;
            $this->accountName = $record->accountName;
            $this->accountNumber = $record->accountNumber;
            $this->limitation = $record->limitation;
            $this->total = $record->total;
            $this->currency = $currencyEntity->code;

            $result = "Добавлен объём $this->amount ( резерв $this->reserve ) для валюты $this->currency с лимитом $this->limitation , использовано всего $this->total";
        }
        if (!$isSuccess) {
            $result = "Ошибка добавления объёма для валюты";
        }

        return $result;
    }

    public function assembleVolume(int $id): bool
    {

        $searcher = new VolumeSearch();
        $foundRecord = $searcher->searchById($id);

        $currencySearcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currency = $currencySearcher->searchById($foundRecord->currencyId);
        $isCurrencyFound = !empty($currency->id);

        if ($isCurrencyFound) {

            $this->isDisable = $foundRecord->isHidden;
            $this->amount = $foundRecord->amount;
            $this->reserve = $foundRecord->reserve;
            $this->accountName = $foundRecord->accountName;
            $this->accountNumber = $foundRecord->accountNumber;
            $this->limitation = $foundRecord->limitation;
            $this->total = $foundRecord->total;

            $this->currency = $currency->code;

        }

        return $isCurrencyFound;
    }

    private function save(): bool
    {
        $record = $this->assembleRecord();

        $isValid = !empty($record->currencyId);
        $result = false;
        if ($isValid) {
            $result = $record->save();
        }

        if ($result) {
            $this->assumeRecord($record);
        }

        return $result;
    }

    private function assembleRecord(): VolumeRecord
    {

        $currencyRecord = $this->getCurrencyRecord();
        $isCurrencyDefined = !empty($currencyRecord->id);

        $record = new VolumeRecord();
        if ($isCurrencyDefined) {
            $record->currencyId = $currencyRecord->id;
        }

        $isValid = $isCurrencyDefined ;
        if ($isValid) {
            $record->isHidden = $this->isDisable;
            $record->amount = $this->amount;
            $record->reserve = $this->reserve;
            $record->accountName = $this->accountName;
            $record->accountNumber = $this->accountNumber;
            $record->limitation = $this->limitation;
            $record->total = $this->total;
        }

        return $record;
    }

    private function assumeRecord(VolumeRecord $record): bool
    {

        $currencySearcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currency = $currencySearcher->searchById($record->currencyId);
        $isCurrencyFound = !empty($currency->id);

        if ($isCurrencyFound) {

            $this->currency = $currency->code;

            $this->isDisable = $record->isHidden;
            $this->amount = $record->amount;
            $this->reserve = $record->reserve;
            $this->accountName = $record->accountName;
            $this->accountNumber = $record->accountNumber;
            $this->limitation = $record->limitation;
            $this->total = $record->total;
        }

        return $isCurrencyFound;

    }

    public function enable(): bool
    {
        $this->isDisable = self::DEFINE_AS_ENABLE;
        $result = $this->save();

        return $result;
    }

    public function disable(): bool
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

    /**
     * @return NamedEntity
     */
    public function getCurrencyRecord(): NamedEntity
    {
        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currencyRecord = $searcher->searchByCode($this->currency);

        return $currencyRecord;
    }

    public function income($income): bool
    {
        $searcher = new VolumeSearch();
        $record = $searcher->searchByCurrency($this->currency);

        $isSuccess = $record->income($income);

        if ($isSuccess) {
            $isSuccess = $this->assumeRecord($record);
        }

        return $isSuccess;
    }

    public function outcome($outcome): bool
    {
        $searcher = new VolumeSearch();
        $record = $searcher->searchByCurrency($this->currency);

        $isSuccess = $record->outcome($outcome);

        if ($isSuccess) {
            $isSuccess = $this->assumeRecord($record);
        }

        return $isSuccess;
    }

    /**
     * @param $currency
     * @return bool
     */
    private function assembleByCurrency(string $currency): bool
    {
        $searcher = new VolumeSearch();

        $incomeRecord = $searcher->searchByCurrency($currency);
        $isSuccess = $this->assumeRecord($incomeRecord);


        return $isSuccess;
    }

}
