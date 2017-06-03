<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 05.04.2017
 * Time: 2:51
 */

namespace Remittance\DataAccess\Search;


use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\TransferRecord;
use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;


class TransferSearch
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = 'transfer';

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;

    /** Прочитать запись из БД
     * @param string $id идентификатор записи
     * @return TransferRecord найденная запись
     */
    public function searchById(string $id):TransferRecord
    {
        $oneParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . TransferRecord::ID
            . ' ,' . TransferRecord::INCOME_AMOUNT
            . ' ,' . TransferRecord::OUTCOME_AMOUNT
            . ' ,' . TransferRecord::REPORT_EMAIL
            . ' ,' . TransferRecord::TRANSFER_NAME
            . ' ,' . TransferRecord::TRANSFER_ACCOUNT
            . ' ,' . TransferRecord::RECEIVE_NAME
            . ' ,' . TransferRecord::RECEIVE_ACCOUNT
            . ' ,' . TransferRecord::DOCUMENT_NUMBER
            . ' ,' . TransferRecord::DOCUMENT_DATE
            . ' ,' . TransferRecord::INCOME_ACCOUNT
            . ' ,' . TransferRecord::OUTCOME_ACCOUNT
            . ' ,' . TransferRecord::TRANSFER_STATUS_ID
            . ' ,' . TransferRecord::STATUS_COMMENT
            . ' ,' . TransferRecord::STATUS_TIME
            . ' ,' . TransferRecord::AWAIT_NAME
            . ' ,' . TransferRecord::AWAIT_ACCOUNT
            . ' FROM '
            . $this->tablename
            . ' WHERE '
            . TransferRecord::ID . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

        $record = SqlHandler::readOneRecord($arguments);

        $result = new TransferRecord();
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result->setByNamedValue($record);
        }

        return $result;
    }

    public function search(array $filterProperties = array(), int $start = 0, int $paging = 0):array
    {

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . TransferRecord::ID
            . ' ,' . TransferRecord::INCOME_AMOUNT
            . ' ,' . TransferRecord::OUTCOME_AMOUNT
            . ' ,' . TransferRecord::REPORT_EMAIL
            . ' ,' . TransferRecord::TRANSFER_NAME
            . ' ,' . TransferRecord::TRANSFER_ACCOUNT
            . ' ,' . TransferRecord::RECEIVE_NAME
            . ' ,' . TransferRecord::RECEIVE_ACCOUNT
            . ' ,' . TransferRecord::DOCUMENT_NUMBER
            . ' ,' . TransferRecord::DOCUMENT_DATE
            . ' ,' . TransferRecord::INCOME_ACCOUNT
            . ' ,' . TransferRecord::OUTCOME_ACCOUNT
            . ' ,' . TransferRecord::TRANSFER_STATUS_ID
            . ' ,' . TransferRecord::STATUS_COMMENT
            . ' ,' . TransferRecord::STATUS_TIME
            . ' ,' . TransferRecord::AWAIT_NAME
            . ' ,' . TransferRecord::AWAIT_ACCOUNT
            . ' FROM '
            . $this->tablename
            . ' ORDER BY ' . TransferRecord::ID . ' DESC'
            . ';';

        $records = SqlHandler::readAllRecords($arguments);

        $isContain = count($records);
        $result = ICommon::EMPTY_ARRAY;
        if ($isContain) {
            foreach ($records as $recordValues) {
                $transfer = new TransferRecord();
                $transfer->setByNamedValue($recordValues);
                $result[] = $transfer;
            }
        }

        return $result;
    }
}
