<?php

namespace Remittance\Manager;


use Remittance\DataAccess\Entity\CurrencyRecord;

class Currency
{
    public $code;
    public $title;
    public $description;
    public $disable;

    public function add(): string
    {

        $record = new CurrencyRecord();

        $isSuccess = $record->addEntity();
        if ($isSuccess) {

            $record->isHidden = $this->disable;
            $record->code = $this->code;
            $record->description = $this->description;
            $record->title = $this->title;

            $isSuccess = $record->mutateEntity();
        }

        $result = '';
        if ($isSuccess) {

            $this->disable = $record->isHidden;
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
}
