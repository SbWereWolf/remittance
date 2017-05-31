<?php

namespace Remittance\Manager;


use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\NamedEntity;
use Remittance\DataAccess\Entity\VolumeRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use const false;
use Remittance\DataAccess\Search\VolumeSearch;

class Volume
{
    /** @var string значение для поднятого флага "отключен" */
    const DEFINE_AS_DISABLE = true;
    /** @var string значение для снятого флага "отключен" */
    const DEFINE_AS_ENABLE = false;
    /** @var string значение по умолчанию для признака "отключен" */
    const DEFAULT_IS_DISABLE = self::DEFINE_AS_ENABLE;

    public $currency = '';
    public $volume = 0;
    public $reserve = 0;
    public $limitation = 0;
    public $total = 0;
    public $isDisable = self::DEFAULT_IS_DISABLE;


    public function add(): string
    {

        $record = new VolumeRecord();
        $isSuccess = $record->addEntity();

        $currencyRecord = new NamedEntity();
        if ($isSuccess) {

            $currencyRecord = $this->getCurrencyRecord();
        }
        $isCurrencyDefined = !empty($currencyRecord->id);

        if ($isCurrencyDefined) {

            $record->isHidden = $this->isDisable;
            $record->volume = $this->volume;
            $record->reserve = $this->reserve;
            $record->limitation = $this->limitation;
            $record->total = $this->total;
            $record->currencyId = $currencyRecord->id;

            $isSuccess = $record->mutateEntity();
        }

        $currencyEntity = new NamedEntity();
        if ($isSuccess) {
            $currencyEntity = $record->getCurrencyRecord();
        }
        $isCurrencyFound = !empty($currencyEntity->id);

        $result = ICommon::EMPTY_VALUE;
        $isSuccess = $isCurrencyFound;
        if ($isSuccess) {

            $this->isDisable = $record->isHidden;
            $this->volume = $record->volume;
            $this->reserve = $record->reserve;
            $this->limitation = $record->limitation;
            $this->total = $record->total;
            $this->currency = $currencyEntity->code;

            $result = "Добавлен объём $this->volume ( резерв $this->reserve ) для валюты $this->currency с лимитом $this->limitation , использовано всего $this->total";
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

        $currency = $foundRecord->getCurrencyRecord();
        $isCurrencyFound = !empty($currency->id);

        if ($isCurrencyFound) {

            $this->isDisable = $foundRecord->isHidden;
            $this->volume = $foundRecord->volume;
            $this->reserve = $foundRecord->reserve;
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
            $record->volume = $this->volume;
            $record->reserve = $this->reserve;
            $record->limitation = $this->limitation;
            $record->total = $this->total;
        }

        return $record;
    }

    private function assumeRecord(VolumeRecord $record)
    {

        $currency = $record->getCurrencyRecord();
        $isCurrencyFound = !empty($currency->id);

        if ($isCurrencyFound) {

            $this->currency = $currency->code;

            $this->isDisable = $record->isHidden;
            $this->volume = $record->volume;
            $this->reserve = $record->reserve;
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

    /**
     * @return NamedEntity
     */
    public function getCurrencyRecord(): NamedEntity
    {
        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currencyRecord = $searcher->searchByCode($this->currency);

        return $currencyRecord;
    }

}
