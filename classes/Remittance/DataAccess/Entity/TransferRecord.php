<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 05.04.2017
 * Time: 2:51
 */

namespace Remittance\DataAccess\Entity;


use Remittance\Core\Common;
use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;


class TransferRecord extends Entity
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = 'transfer';

    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'transfer_id';
    const INCOME_AMOUNT = 'income_amount';
    const OUTCOME_AMOUNT = 'outcome_amount';
    const REPORT_EMAIL = 'report_email';
    const TRANSFER_NAME = 'transfer_name';
    const TRANSFER_ACCOUNT = 'transfer_account';
    const RECEIVE_NAME = 'receive_name';
    const RECEIVE_ACCOUNT = 'receive_account';
    const DOCUMENT_NUMBER = 'document_number';
    const DOCUMENT_DATE = 'document_date';
    const INCOME_ACCOUNT = 'income_account';
    const OUTCOME_ACCOUNT = 'outcome_account';
    const STATUS = 'status';
    const STATUS_COMMENT = 'status_comment';
    const STATUS_TIME = 'status_time';

    public $incomeAmount = 0;
    public $outcomeAmount = 0;
    public $reportEmail = '';
    public $transferName = '';
    public $transferAccount = '';
    public $receiveName = '';
    public $receiveAccount = '';
    public $documentNumber = '';
    public $documentDate = '';
    public $incomeAccount = '';
    public $outcomeAccount = '';
    public $status = 0;
    public $statusComment = '';
    public $statusTime = '';
    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    protected $classname = self::class;

    /** Прочитать запись из БД
     * @param string $id идентификатор записи
     * @return bool успех выполнения
     */
    protected function loadById(string $id):bool
    {
        $oneParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . self::ID
            . ' ,' . self::INCOME_AMOUNT
            . ' ,' . self::OUTCOME_AMOUNT
            . ' ,' . self::REPORT_EMAIL
            . ' ,' . self::TRANSFER_NAME
            . ' ,' . self::TRANSFER_ACCOUNT
            . ' ,' . self::RECEIVE_NAME
            . ' ,' . self::RECEIVE_ACCOUNT
            . ' ,' . self::DOCUMENT_NUMBER
            . ' ,' . self::DOCUMENT_DATE
            . ' ,' . self::INCOME_ACCOUNT
            . ' ,' . self::OUTCOME_ACCOUNT
            . ' ,' . self::STATUS
            . ' ,' . self::STATUS_COMMENT
            . ' ,' . self::STATUS_TIME
            . ' FROM '
            . $this->tablename
            . ' WHERE '
            . self::ID . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

        $record = SqlHandler::readOneRecord($arguments);

        $result = false;
        if ($record != ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
    }

    /** Установить свойства экземпляра в соответствии со значениями
     * @param array $namedValue массив значений
     * @return bool успех выполнения
     */
    public function setByNamedValue(array $namedValue):bool
    {

        $result = parent::setByNamedValue($namedValue);

        $this->incomeAmount = SqlHandler::setIfExists(self::INCOME_AMOUNT, $namedValue);
        $this->outcomeAmount = SqlHandler::setIfExists(self::OUTCOME_AMOUNT, $namedValue);
        $this->reportEmail = SqlHandler::setIfExists(self::REPORT_EMAIL, $namedValue);
        $this->transferName = SqlHandler::setIfExists(self::TRANSFER_NAME, $namedValue);
        $this->transferAccount = SqlHandler::setIfExists(self::TRANSFER_ACCOUNT, $namedValue);
        $this->receiveName = SqlHandler::setIfExists(self::RECEIVE_NAME, $namedValue);
        $this->receiveAccount = SqlHandler::setIfExists(self::RECEIVE_ACCOUNT, $namedValue);
        $this->documentNumber = SqlHandler::setIfExists(self::DOCUMENT_NUMBER, $namedValue);
        $this->documentDate = SqlHandler::setIfExists(self::DOCUMENT_DATE, $namedValue);
        $this->incomeAccount = SqlHandler::setIfExists(self::INCOME_ACCOUNT, $namedValue);
        $this->outcomeAccount = SqlHandler::setIfExists(self::OUTCOME_ACCOUNT, $namedValue);
        $this->status = SqlHandler::setIfExists(self::STATUS, $namedValue);
        $this->statusComment = SqlHandler::setIfExists(self::STATUS_COMMENT, $namedValue);
        $this->statusTime = SqlHandler::setIfExists(self::STATUS_TIME, $namedValue);

        return $result;
    }

    /** Формирует массив из свойств экземпляра
     * @return array массив свойств экземпляра
     */
    public function toEntity():array
    {
        parent::toEntity();

        $result [self::INCOME_AMOUNT] = $this->incomeAmount;
        $result [self::OUTCOME_AMOUNT] = $this->outcomeAmount;
        $result [self::REPORT_EMAIL] = $this->reportEmail;
        $result [self::TRANSFER_NAME] = $this->transferName;
        $result [self::TRANSFER_ACCOUNT] = $this->transferAccount;
        $result [self::RECEIVE_NAME] = $this->receiveName;
        $result [self::RECEIVE_ACCOUNT] = $this->receiveAccount;
        $result [self::DOCUMENT_NUMBER] = $this->documentNumber;
        $result [self::DOCUMENT_DATE] = $this->documentDate;
        $result [self::INCOME_ACCOUNT] = $this->incomeAccount;
        $result [self::OUTCOME_ACCOUNT] = $this->outcomeAccount;
        $result [self::STATUS] = $this->status;
        $result [self::STATUS_COMMENT] = $this->statusComment;
        $result [self::STATUS_TIME] = $this->statusTime;

        return $result;
    }

    /** Обновить данные в БД
     * @return bool успех выполнения
     */
    protected function updateEntity():bool
    {
        $id = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_STR);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

        $incomeAmount = SqlHandler::setBindParameter(':INCOME_AMOUNT', $this->incomeAmount, \PDO::PARAM_STR);
        $outcomeAmount = SqlHandler::setBindParameter(':OUTCOME_AMOUNT', $this->outcomeAmount, \PDO::PARAM_STR);
        $reportEmail = SqlHandler::setBindParameter(':REPORT_EMAIL', $this->reportEmail, \PDO::PARAM_STR);
        $transferName = SqlHandler::setBindParameter(':TRANSFER_NAME', $this->transferName, \PDO::PARAM_STR);
        $transferAccount = SqlHandler::setBindParameter(':TRANSFER_ACCOUNT', $this->transferAccount, \PDO::PARAM_STR);
        $receiveName = SqlHandler::setBindParameter(':RECEIVE_NAME', $this->receiveName, \PDO::PARAM_STR);
        $receiveAccount = SqlHandler::setBindParameter(':RECEIVE_ACCOUNT', $this->receiveAccount, \PDO::PARAM_STR);
        $documentNumber = SqlHandler::setBindParameter(':DOCUMENT_NUMBER', $this->documentNumber, \PDO::PARAM_STR);
        $documentDate = SqlHandler::setBindParameter(':DOCUMENT_DATE', $this->documentDate, \PDO::PARAM_STR);
        $incomeAccount = SqlHandler::setBindParameter(':INCOME_ACCOUNT', $this->incomeAccount, \PDO::PARAM_STR);
        $outcomeAccount = SqlHandler::setBindParameter(':OUTCOME_ACCOUNT', $this->outcomeAccount, \PDO::PARAM_STR);
        $status = SqlHandler::setBindParameter(':STATUS', $this->status, \PDO::PARAM_STR);
        $statusComment = SqlHandler::setBindParameter(':STATUS_COMMENT', $this->statusComment, \PDO::PARAM_STR);
        $statusTime = SqlHandler::setBindParameter(':STATUS_TIME', $this->statusTime, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::INCOME_AMOUNT . ' = ' . $incomeAmount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::OUTCOME_AMOUNT . ' = ' . $outcomeAmount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::REPORT_EMAIL . ' = ' . $reportEmail[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TRANSFER_NAME . ' = ' . $transferName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TRANSFER_ACCOUNT . ' = ' . $transferAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::RECEIVE_NAME . ' = ' . $receiveName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::RECEIVE_ACCOUNT . ' = ' . $receiveAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::DOCUMENT_NUMBER . ' = ' . $documentNumber[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::DOCUMENT_DATE . ' = ' . $documentDate[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::INCOME_ACCOUNT . ' = ' . $incomeAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::OUTCOME_ACCOUNT . ' = ' . $outcomeAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS . ' = ' . $status[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_COMMENT . ' = ' . $statusComment[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_TIME . ' = ' . $statusTime[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
            . ' WHERE '
            . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::INCOME_AMOUNT
            . ' , ' . self::OUTCOME_AMOUNT
            . ' , ' . self::REPORT_EMAIL
            . ' , ' . self::TRANSFER_NAME
            . ' , ' . self::TRANSFER_ACCOUNT
            . ' , ' . self::RECEIVE_NAME
            . ' , ' . self::RECEIVE_ACCOUNT
            . ' , ' . self::DOCUMENT_NUMBER
            . ' , ' . self::DOCUMENT_DATE
            . ' , ' . self::INCOME_ACCOUNT
            . ' , ' . self::OUTCOME_ACCOUNT
            . ' , ' . self::STATUS
            . ' , ' . self::STATUS_COMMENT
            . ' , ' . self::STATUS_TIME
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $incomeAmount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $outcomeAmount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $reportEmail;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $transferName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $transferAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $receiveName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $receiveAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $documentNumber;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $documentDate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $incomeAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $outcomeAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $status;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $statusComment;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $statusTime;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHidden;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $id;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record !== ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
    }
}
