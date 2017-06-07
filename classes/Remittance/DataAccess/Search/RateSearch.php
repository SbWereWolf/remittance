<?php

namespace Remittance\DataAccess\Search;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\RateRecord;
use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;


class RateSearch
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = 'rate';

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;

    /** Прочитать запись из БД
     * @param string $id идентификатор записи
     * @return RateRecord найденная запись
     */
    public function searchById(string $id): RateRecord
    {
        $oneParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . RateRecord::ID
            . ' ,' . RateRecord::IS_HIDDEN
            . ' ,' . RateRecord::SOURCE_CURRENCY
            . ' ,' . RateRecord::TARGET_CURRENCY
            . ' ,' . RateRecord::RATIO
            . ' ,' . RateRecord::FEE
            . ' ,' . RateRecord::IS_DEFAULT
            . ' FROM '
            . $this->tablename
            . ' WHERE '
            . RateRecord::ID . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

        $record = SqlHandler::readOneRecord($arguments);

        $result = new RateRecord();
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result->setByNamedValue($record);
        }

        return $result;
    }

    public function search(array $filterProperties = array(), int $start = 0, int $paging = 0): array
    {

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . RateRecord::ID
            . ' ,' . RateRecord::IS_HIDDEN
            . ' ,' . RateRecord::SOURCE_CURRENCY
            . ' ,' . RateRecord::TARGET_CURRENCY
            . ' ,' . RateRecord::RATIO
            . ' ,' . RateRecord::FEE
            . ' ,' . RateRecord::IS_DEFAULT
            . ' FROM '
            . $this->tablename
            . ' ORDER BY ' . RateRecord::ID . ' DESC'
            . ';';

        $records = SqlHandler::readAllRecords($arguments);

        $isContain = Common::isValidArray($records);
        $result = ICommon::EMPTY_ARRAY;
        if ($isContain) {
            foreach ($records as $recordValues) {
                $rateRecord = new RateRecord();
                $rateRecord->setByNamedValue($recordValues);
                $result[] = $rateRecord;
            }
        }

        return $result;
    }

    public function searchExchangeRate(string $source, string $target): array
    {
        $sourceCurrency = SqlHandler::setBindParameter(':SOURCE', $source, \PDO::PARAM_STR);
        $targetCurrency = SqlHandler::setBindParameter(':TARGET', $target, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . 'R.' . RateRecord::ID
            . ' , R.' . RateRecord::IS_HIDDEN
            . ' , R.' . RateRecord::SOURCE_CURRENCY
            . ' , R.' . RateRecord::TARGET_CURRENCY
            . ' , R.' . RateRecord::RATIO
            . ' , R.' . RateRecord::FEE
            . ' , R.' . RateRecord::IS_DEFAULT
            . ' FROM '
            . $this->tablename . ' AS R '
            . ' JOIN ' . CurrencyRecord::TABLE_NAME . ' AS CS '
            . ' ON R.' . RateRecord::SOURCE_CURRENCY . ' = CS.' . CurrencyRecord::ID
            . ' JOIN ' . CurrencyRecord::TABLE_NAME . ' AS CT '
            . ' ON R.' . RateRecord::TARGET_CURRENCY . ' = CT.' . CurrencyRecord::ID
            . ' WHERE '
            . ' CS.' . CurrencyRecord::CODE . ' = ' . $sourceCurrency[ISqlHandler::PLACEHOLDER]
            . ' AND CT.' . CurrencyRecord::CODE . ' = ' . $targetCurrency[ISqlHandler::PLACEHOLDER]
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $sourceCurrency;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $targetCurrency;

        $record = SqlHandler::readOneRecord($arguments);

        $result = new RateRecord();
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result->setByNamedValue($record);
        }

        return $result;
    }
}
