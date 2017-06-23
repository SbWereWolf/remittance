<?php

namespace Remittance\DataAccess\Search;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\TransferRecord;
use Remittance\DataAccess\Entity\TransferStatusRecord;
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
            . ' ,' . TransferRecord::INCOME_CURRENCY
            . ' ,' . TransferRecord::OUTCOME_CURRENCY
            . ' ,' . TransferRecord::TRANSFER_STATUS
            . ' ,' . TransferRecord::STATUS_COMMENT
            . ' ,' . TransferRecord::STATUS_TIME
            . ' ,' . TransferRecord::AWAIT_NAME
            . ' ,' . TransferRecord::AWAIT_ACCOUNT
            . ' ,' . TransferRecord::FEE
            . ' ,' . TransferRecord::PROCEED_ACCOUNT
            . ' ,' . TransferRecord::PROCEED_NAME
            . ' ,' . TransferRecord::BODY
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
            . ' ,' . TransferRecord::INCOME_CURRENCY
            . ' ,' . TransferRecord::OUTCOME_CURRENCY
            . ' ,' . TransferRecord::TRANSFER_STATUS
            . ' ,' . TransferRecord::STATUS_COMMENT
            . ' ,' . TransferRecord::STATUS_TIME
            . ' ,' . TransferRecord::AWAIT_NAME
            . ' ,' . TransferRecord::AWAIT_ACCOUNT
            . ' ,' . TransferRecord::FEE
            . ' ,' . TransferRecord::PROCEED_ACCOUNT
            . ' ,' . TransferRecord::PROCEED_NAME
            . ' ,' . TransferRecord::BODY
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

    public function searchByStatus(string $status): array
    {
        $statusCode = SqlHandler::setBindParameter(':CODE', $status, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . 'T.' . TransferRecord::ID
            . ' , T.' . TransferRecord::IS_HIDDEN
            . ' , T.' . TransferRecord::INCOME_AMOUNT
            . ' , T.' . TransferRecord::OUTCOME_AMOUNT
            . ' , T.' . TransferRecord::REPORT_EMAIL
            . ' , T.' . TransferRecord::TRANSFER_NAME
            . ' , T.' . TransferRecord::TRANSFER_ACCOUNT
            . ' , T.' . TransferRecord::RECEIVE_NAME
            . ' , T.' . TransferRecord::RECEIVE_ACCOUNT
            . ' , T.' . TransferRecord::DOCUMENT_NUMBER
            . ' , T.' . TransferRecord::DOCUMENT_DATE
            . ' , T.' . TransferRecord::INCOME_CURRENCY
            . ' , T.' . TransferRecord::OUTCOME_CURRENCY
            . ' , T.' . TransferRecord::TRANSFER_STATUS
            . ' , T.' . TransferRecord::STATUS_COMMENT
            . ' , T.' . TransferRecord::STATUS_TIME
            . ' , T.' . TransferRecord::AWAIT_NAME
            . ' , T.' . TransferRecord::AWAIT_ACCOUNT
            . ' , T.' . TransferRecord::FEE
            . ' , T.' . TransferRecord::PROCEED_ACCOUNT
            . ' , T.' . TransferRecord::PROCEED_NAME
            . ' , T.' . TransferRecord::BODY
            . ' FROM '
            . $this->tablename . ' AS T '
            . ' JOIN ' . TransferStatusRecord::TABLE_NAME . ' AS S '
            . ' ON T.' . TransferRecord::TRANSFER_STATUS . ' = S.' . TransferStatusRecord::ID
            . ' WHERE '
            . ' S.' . TransferStatusRecord::CODE . ' = ' . $statusCode[ISqlHandler::PLACEHOLDER]
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $statusCode;

        $records = SqlHandler::readAllRecords($arguments);

        $isContain = Common::isValidArray($records);
        $result = ICommon::EMPTY_ARRAY;
        if ($isContain) {
            foreach ($records as $recordValues) {
                $rateRecord = new TransferRecord();
                $rateRecord->setByNamedValue($recordValues);
                $result[] = $rateRecord;
            }
        }

        return $result;
    }
}
