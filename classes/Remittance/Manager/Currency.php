<?php

namespace Remittance\Manager;


use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\NamedEntity;
use Remittance\DataAccess\Search\NamedEntitySearch;

class Currency
{

    /** @var string значение для поднятого флага "является скрытым" */
    const DEFINE_AS_DISABLE = true;
    /** @var string значение для поднятого флага "является скрытым" */
    const DEFINE_AS_NOT_DISABLE = false;
    /** @var string значение по умолчанию для признака "является скрытым" */
    const DEFAULT_IS_DISABLE = self::DEFINE_AS_NOT_DISABLE;

    public $code = '';
    public $title = '';
    public $description = '';
    public $isDisable = self::DEFAULT_IS_DISABLE;

    public function add(): string
    {

        $record = new CurrencyRecord();

        $isSuccess = $record->addEntity();
        if ($isSuccess) {

            $record->isHidden = $this->isDisable;
            $record->code = $this->code;
            $record->description = $this->description;
            $record->title = $this->title;

            $isSuccess = $record->mutateEntity();
        }

        $result = '';
        if ($isSuccess) {

            $this->isDisable = $record->isHidden;
            $this->code = $record->code;
            $this->description = $record->description;
            $this->title = $record->title;

            $result = "Добавлена валюта $this->title с кодом $this->code";
        }
        if (!$isSuccess) {
            $result = "Ошибка добавления валюты";
        }

        return $result;
    }

    public function assembleCurrency($id): bool
    {
        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $foundRecord = $searcher->searchById($id);
        $result = $this->assumeNamedEntity($foundRecord);

        return $result;
    }

    /** Принять значения свойств из записи базы данных
     * @param NamedEntity $record запись базы данных
     * @return bool успех выполнения операции
     */
    public function assumeNamedEntity(NamedEntity $record): bool
    {

        $this->isDisable = $record->isHidden;
        $this->code = $record->code;
        $this->description = $record->description;
        $this->title = $record->title;

        return true;
    }

    public function disable():bool
    {
        $this->isDisable = self::DEFINE_AS_DISABLE;
        $result = $this->save();

        return $result;
    }

    public function enable():bool
    {
        $this->isDisable = self::DEFINE_AS_NOT_DISABLE;
        $result = $this->save();

        return $result;
    }

    public function store(): string
    {
        $Success = $this->save();

        $result = $Success ? 'Успешно сохранено' : 'Ошибка сохранения';

        return $result;
    }

    private function save():bool
    {
        $record = $this->assembleRecord();
        $result = $record->save();

        if ($result) {
            $this->assumeRecord($record);
        }

        return $result;
    }

    private function assembleRecord():CurrencyRecord
    {
        $record = new CurrencyRecord();

        $record->isHidden = $this->isDisable;
        $record->code = $this->code;
        $record->description = $this->description;
        $record->title = $this->title;

        return $record;
    }

    private function assumeRecord(CurrencyRecord $record):bool
    {
        $this->isDisable = $record->isHidden;
        $this->code = $record->code;
        $this->description = $record->description;
        $this->title = $record->title;

        return true;
    }
}
