<?php

namespace Remittance\DataAccess\Search;


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
            . ' ,' . RateRecord::SOURCE_CURRENCY
            . ' ,' . RateRecord::TARGET_CURRENCY
            . ' ,' . RateRecord::EXCHANGE_RATE
            . ' ,' . RateRecord::FEE
            . ' ,' . RateRecord::EFFECTIVE_RATE
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
            . ' ,' . RateRecord::SOURCE_CURRENCY
            . ' ,' . RateRecord::TARGET_CURRENCY
            . ' ,' . RateRecord::EXCHANGE_RATE
            . ' ,' . RateRecord::FEE
            . ' ,' . RateRecord::EFFECTIVE_RATE
            . ' ,' . RateRecord::IS_DEFAULT
            . ' FROM '
            . $this->tablename
            . ' ORDER BY ' . RateRecord::ID . ' DESC'
            . ';';

        $records = SqlHandler::readAllRecords($arguments);

        $isContain = count($records);
        $result = array();
        if ($isContain) {
            foreach ($records as $recordValues) {
                $transfer = new RateRecord();
                $transfer->setByNamedValue($recordValues);
                $result[] = $transfer;
            }
        }

        return $result;
    }
}
