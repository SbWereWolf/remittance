<?php

namespace Remittance\DataAccess\Entity;


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
    const INCOME_ACCOUNT = 'income_currency';
    const OUTCOME_ACCOUNT = 'outcome_currency';
    const TRANSFER_STATUS_ID = 'transfer_status_id';
    const STATUS_COMMENT = 'status_comment';
    const STATUS_TIME = 'status_time';
    const AWAIT_NAME = 'await_name';
    const AWAIT_ACCOUNT = 'await_account';
    const FEE = 'fee';
    const PROCEED_ACCOUNT = 'proceed_account';
    const PROCEED_NAME = 'proceed_name';
    const BODY = 'body';

    public $incomeAmount = 0;
    public $outcomeAmount = 0;
    public $reportEmail = '';
    public $transferName = '';
    public $transferAccount = '';
    public $receiveName = '';
    public $receiveAccount = '';
    public $documentNumber = '';
    public $documentDate = '';
    public $incomeCurrency = '';
    public $outcomeCurrency = '';
    public $transferStatusId = 0;
    public $statusComment = '';
    public $statusTime = '';
    public $awaitName = '';
    public $awaitAccount = '';
    public $fee = 0;
    public $proceedAccount = '';
    public $proceedName = '';
    public $body = 0;

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    /** @var string имя класса, используется в мутировании */
    protected $classname = self::class;

    public static function adopt($object): TransferRecord
    {

        return $object;

    }

    /** Формирует массив из свойств экземпляра
     * @return array массив свойств экземпляра
     */
    public function toEntity(): array
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
        $result [self::INCOME_ACCOUNT] = $this->incomeCurrency;
        $result [self::OUTCOME_ACCOUNT] = $this->outcomeCurrency;
        $result [self::TRANSFER_STATUS_ID] = intval($this->transferStatusId);
        $result [self::STATUS_COMMENT] = $this->statusComment;
        $result [self::STATUS_TIME] = $this->statusTime;
        $result [self::AWAIT_NAME] = $this->awaitName;
        $result [self::AWAIT_ACCOUNT] = $this->awaitAccount;
        $result [self::FEE] = floatval($this->fee);
        $result [self::PROCEED_ACCOUNT] = $this->proceedAccount;
        $result [self::PROCEED_NAME] = $this->proceedName;
        $result [self::BODY] = floatval($this->body);


        return $result;
    }

    public function save(): bool
    {

        $incomeAmount = SqlHandler::setBindParameter(':INCOME_AMOUNT', $this->incomeAmount, \PDO::PARAM_STR);
        $outcomeAmount = SqlHandler::setBindParameter(':OUTCOME_AMOUNT', $this->outcomeAmount, \PDO::PARAM_STR);
        $reportEmail = SqlHandler::setBindParameter(':REPORT_EMAIL', $this->reportEmail, \PDO::PARAM_STR);
        $transferName = SqlHandler::setBindParameter(':TRANSFER_NAME', $this->transferName, \PDO::PARAM_STR);
        $transferAccount = SqlHandler::setBindParameter(':TRANSFER_ACCOUNT', $this->transferAccount, \PDO::PARAM_STR);
        $receiveName = SqlHandler::setBindParameter(':RECEIVE_NAME', $this->receiveName, \PDO::PARAM_STR);
        $receiveAccount = SqlHandler::setBindParameter(':RECEIVE_ACCOUNT', $this->receiveAccount, \PDO::PARAM_STR);
        $documentNumber = SqlHandler::setBindParameter(':DOCUMENT_NUMBER', $this->documentNumber, \PDO::PARAM_STR);
        $documentDate = SqlHandler::setBindParameter(':DOCUMENT_DATE', $this->documentDate, \PDO::PARAM_STR);
        $incomeAccount = SqlHandler::setBindParameter(':INCOME_ACCOUNT', $this->incomeCurrency, \PDO::PARAM_STR);
        $outcomeAccount = SqlHandler::setBindParameter(':OUTCOME_ACCOUNT', $this->outcomeCurrency, \PDO::PARAM_STR);
        $status = SqlHandler::setBindParameter(':STATUS_ID', $this->transferStatusId, \PDO::PARAM_INT);
        $statusComment = SqlHandler::setBindParameter(':STATUS_COMMENT', $this->statusComment, \PDO::PARAM_STR);
        $statusTime = SqlHandler::setBindParameter(':STATUS_TIME', $this->statusTime, \PDO::PARAM_STR);
        $awaitName = SqlHandler::setBindParameter(':AWAIT_NAME', $this->awaitName, \PDO::PARAM_STR);
        $awaitAccount = SqlHandler::setBindParameter(':AWAIT_ACCOUNT', $this->awaitAccount, \PDO::PARAM_STR);
        $fee = SqlHandler::setBindParameter(':FEE', $this->awaitAccount, \PDO::PARAM_STR);
        $proceed_account = SqlHandler::setBindParameter(':PROCEED_ACCOUNT', $this->awaitAccount, \PDO::PARAM_STR);
        $proceed_name = SqlHandler::setBindParameter(':PROCEED_NAME', $this->awaitAccount, \PDO::PARAM_STR);
        $body = SqlHandler::setBindParameter(':BODY', $this->body, \PDO::PARAM_STR);


        $isHiddenFilterValue = intval(self::DEFINE_AS_NOT_HIDDEN);

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
            . ' , ' . self::TRANSFER_STATUS_ID . ' = ' . $status[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_COMMENT . ' = ' . $statusComment[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_TIME . ' = ' . $statusTime[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AWAIT_NAME . ' = ' . $statusTime[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AWAIT_ACCOUNT . ' = ' . $statusTime[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::FEE . ' = CAST(' . $fee[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::PROCEED_ACCOUNT . ' = ' . $proceed_account[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::PROCEED_NAME . ' = ' . $proceed_name[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::BODY . ' = CAST(' . $body[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'

            . ' WHERE '
            . self::IS_HIDDEN . ' = ' . $isHiddenFilterValue
            . ' AND ' . self::DOCUMENT_NUMBER . ' = ' . $documentNumber[ISqlHandler::PLACEHOLDER]
            . ' AND ' . self::DOCUMENT_DATE . ' = ' . $documentDate[ISqlHandler::PLACEHOLDER]
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
            . ' , ' . self::TRANSFER_STATUS_ID
            . ' , ' . self::STATUS_COMMENT
            . ' , ' . self::STATUS_TIME
            . ' , ' . self::AWAIT_NAME
            . ' , ' . self::AWAIT_ACCOUNT
            . ' , ' . self::FEE
            . ' , ' . self::PROCEED_ACCOUNT
            . ' , ' . self::PROCEED_NAME
            . ' , ' . self::BODY
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
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $awaitName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $awaitAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $fee;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $proceed_account;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $proceed_name;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $body;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record !== ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;

    }

    /** Прочитать запись из БД
     * @param string $id идентификатор записи
     * @return bool успех выполнения
     */
    protected function loadById(string $id): bool
    {
        $oneParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . self::ID
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
            . ' , ' . self::TRANSFER_STATUS_ID
            . ' , ' . self::STATUS_COMMENT
            . ' , ' . self::STATUS_TIME
            . ' , ' . self::AWAIT_NAME
            . ' , ' . self::AWAIT_ACCOUNT
            . ' , ' . self::FEE
            . ' , ' . self::PROCEED_ACCOUNT
            . ' , ' . self::PROCEED_NAME
            . ' , ' . self::BODY
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
    public function setByNamedValue(array $namedValue): bool
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
        $this->incomeCurrency = SqlHandler::setIfExists(self::INCOME_ACCOUNT, $namedValue);
        $this->outcomeCurrency = SqlHandler::setIfExists(self::OUTCOME_ACCOUNT, $namedValue);
        $this->transferStatusId = intval(SqlHandler::setIfExists(self::TRANSFER_STATUS_ID, $namedValue));
        $this->statusComment = SqlHandler::setIfExists(self::STATUS_COMMENT, $namedValue);
        $this->statusTime = SqlHandler::setIfExists(self::STATUS_TIME, $namedValue);
        $this->awaitName = SqlHandler::setIfExists(self::AWAIT_NAME, $namedValue);
        $this->awaitAccount = SqlHandler::setIfExists(self::AWAIT_ACCOUNT, $namedValue);
        $this->fee = floatval(SqlHandler::setIfExists(self::FEE, $namedValue));
        $this->proceedAccount = SqlHandler::setIfExists(self::PROCEED_ACCOUNT, $namedValue);
        $this->proceedName = SqlHandler::setIfExists(self::PROCEED_NAME, $namedValue);
        $this->body = floatval(SqlHandler::setIfExists(self::BODY, $namedValue));


        return $result;
    }

    /** Обновить данные в БД
     * @return bool успех выполнения
     */
    protected function updateEntity(): bool
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
        $incomeAccount = SqlHandler::setBindParameter(':INCOME_ACCOUNT', $this->incomeCurrency, \PDO::PARAM_STR);
        $outcomeAccount = SqlHandler::setBindParameter(':OUTCOME_ACCOUNT', $this->outcomeCurrency, \PDO::PARAM_STR);
        $status = SqlHandler::setBindParameter(':STATUS_ID', $this->transferStatusId, \PDO::PARAM_INT);
        $statusComment = SqlHandler::setBindParameter(':STATUS_COMMENT', $this->statusComment, \PDO::PARAM_STR);
        $statusTime = SqlHandler::setBindParameter(':STATUS_TIME', $this->statusTime, \PDO::PARAM_STR);
        $awaitName = SqlHandler::setBindParameter(':AWAIT_NAME', $this->awaitName, \PDO::PARAM_STR);
        $awaitAccount = SqlHandler::setBindParameter(':AWAIT_ACCOUNT', $this->awaitAccount, \PDO::PARAM_STR);
        $fee = SqlHandler::setBindParameter(':FEE', $this->fee, \PDO::PARAM_STR);
        $proceed_account = SqlHandler::setBindParameter(':PROCEED_ACCOUNT', $this->proceedAccount, \PDO::PARAM_STR);
        $proceed_name = SqlHandler::setBindParameter(':PROCEED_NAME', $this->proceedName, \PDO::PARAM_STR);
        $body = SqlHandler::setBindParameter(':BODY', $this->body, \PDO::PARAM_STR);


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
            . ' , ' . self::TRANSFER_STATUS_ID . ' = ' . $status[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_COMMENT . ' = ' . $statusComment[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_TIME . ' = ' . $statusTime[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AWAIT_NAME . ' = ' . $awaitName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AWAIT_ACCOUNT . ' = ' . $awaitAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::FEE . ' = CAST(' . $fee[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::PROCEED_ACCOUNT . ' = ' . $proceed_account[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::PROCEED_NAME . ' = ' . $proceed_name[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::BODY . ' = CAST(' . $body[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
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
            . ' , ' . self::TRANSFER_STATUS_ID
            . ' , ' . self::STATUS_COMMENT
            . ' , ' . self::STATUS_TIME
            . ' , ' . self::AWAIT_NAME
            . ' , ' . self::AWAIT_ACCOUNT
            . ' , ' . self::FEE
            . ' , ' . self::PROCEED_ACCOUNT
            . ' , ' . self::PROCEED_NAME
            . ' , ' . self::BODY
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
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $awaitName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $awaitAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $fee;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $proceed_account;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $proceed_name;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $body;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record !== ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
    }
}
