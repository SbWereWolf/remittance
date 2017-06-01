<?php

namespace Remittance\DataAccess\Search;


use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\VolumeRecord;
use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;


class VolumeSearch
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = VolumeRecord::TABLE_NAME;

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;

    /** Прочитать запись из БД
     * @param string $id идентификатор записи
     * @return VolumeRecord найденная запись
     */
    public function searchById(string $id): VolumeRecord
    {
        $oneParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . VolumeRecord::ID
            . ' ,' . VolumeRecord::IS_HIDDEN
            . ' ,' . VolumeRecord::CURRENCY_ID
            . ' ,' . VolumeRecord::AMOUNT
            . ' ,' . VolumeRecord::RESERVE
            . ' ,' . VolumeRecord::LIMITATION
            . ' ,' . VolumeRecord::TOTAL
            . ' FROM '
            . $this->tablename
            . ' WHERE '
            . VolumeRecord::ID . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

        $record = SqlHandler::readOneRecord($arguments);

        $result = new VolumeRecord();
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result->setByNamedValue($record);
        }

        return $result;
    }

    public function search(array $filterProperties = array(), int $start = 0, int $paging = 0): array
    {

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . VolumeRecord::ID
            . ' ,' . VolumeRecord::IS_HIDDEN
            . ' ,' . VolumeRecord::CURRENCY_ID
            . ' ,' . VolumeRecord::AMOUNT
            . ' ,' . VolumeRecord::RESERVE
            . ' ,' . VolumeRecord::LIMITATION
            . ' ,' . VolumeRecord::TOTAL
            . ' FROM '
            . $this->tablename
            . ' ORDER BY ' . VolumeRecord::ID . ' DESC'
            . ';';

        $records = SqlHandler::readAllRecords($arguments);

        $isContain = count($records);
        $result = ICommon::EMPTY_ARRAY;
        if ($isContain) {
            foreach ($records as $recordValues) {
                $volumeRecord = new VolumeRecord();
                $volumeRecord->setByNamedValue($recordValues);
                $result[] = $volumeRecord;
            }
        }

        return $result;
    }

    public function searchByCurrency(string $currencyCode): VolumeRecord
    {
        $currency = SqlHandler::setBindParameter(':CURRENCY', $currencyCode, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . 'R.' . VolumeRecord::ID
            . ' , R.' . VolumeRecord::IS_HIDDEN
            . ' , R.' . VolumeRecord::CURRENCY_ID
            . ' , R.' . VolumeRecord::AMOUNT
            . ' , R.' . VolumeRecord::RESERVE
            . ' , R.' . VolumeRecord::LIMITATION
            . ' , R.' . VolumeRecord::TOTAL
            . ' FROM '
            . $this->tablename . ' AS R '
            . ' JOIN ' . CurrencyRecord::TABLE_NAME . ' AS C '
            . ' ON R.' . VolumeRecord::CURRENCY_ID . ' = C.' . CurrencyRecord::ID
            . ' WHERE '
            . ' C.' . CurrencyRecord::CODE . ' = ' . $currency[ISqlHandler::PLACEHOLDER]
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $currency;

        $record = SqlHandler::readOneRecord($arguments);

        $result = new VolumeRecord();
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result->setByNamedValue($record);
        }

        return $result;
    }
}
