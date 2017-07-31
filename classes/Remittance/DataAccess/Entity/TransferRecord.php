<?php

namespace Remittance\DataAccess\Entity;


use Remittance\DataAccess\Logic\IDbFormatter;
use Remittance\DataAccess\Logic\InputFormatter;
use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\OutputFormatter;
use Remittance\DataAccess\Logic\SqlHandler;


class TransferRecord extends Entity
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = 'transfer';

    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'transfer_id';

    const PLACEMENT_DATE = 'placement_date';
    const DOCUMENT_NUMBER = 'document_number';
    const DOCUMENT_DATE = 'document_date';
    const REPORT_EMAIL = 'report_email';
    const TRANSFER_STATUS_ID = 'transfer_status_id';
    const STATUS_COMMENT = 'status_comment';
    const STATUS_TIME = 'status_time';
    const INCOME_CURRENCY_ID = 'income_currency_id';
    const TRANSFER_ACCOUNT = 'transfer_account';
    const TRANSFER_NAME = 'transfer_name';
    const INCOME_AMOUNT = 'income_amount';
    const AWAIT_ACCOUNT = 'await_account';
    const AWAIT_NAME = 'await_name';
    const FEE = 'fee';
    const BODY = 'body';
    const OUTCOME_CURRENCY_ID = 'outcome_currency_id';
    const PROCEED_ACCOUNT = 'proceed_account';
    const PROCEED_NAME = 'proceed_name';
    const OUTCOME_AMOUNT = 'outcome_amount';
    const COST = 'cost';
    const RECEIVE_ACCOUNT = 'receive_account';
    const RECEIVE_NAME = 'receive_name';

    public $placementDate = null;
    public $documentNumber = '';
    public $documentDate = null;
    public $reportEmail = '';
    public $transferStatus = null;
    public $statusComment = '';
    public $statusTime = null;
    public $incomeCurrency = null;
    public $transferAccount = '';
    public $transferName = '';
    public $incomeAmount = null;
    public $awaitAccount = '';
    public $awaitName = '';
    public $fee = null;
    public $body = null;
    public $outcomeCurrency = null;
    public $proceedAccount = '';
    public $proceedName = '';
    public $outcomeAmount = null;
    public $cost = null;
    public $receiveAccount = '';
    public $receiveName = '';

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
        $result = parent::toEntity();

        $result [self::INCOME_AMOUNT] = $this->incomeAmount;
        $result [self::OUTCOME_AMOUNT] = $this->outcomeAmount;
        $result [self::REPORT_EMAIL] = $this->reportEmail;
        $result [self::TRANSFER_NAME] = $this->transferName;
        $result [self::TRANSFER_ACCOUNT] = $this->transferAccount;
        $result [self::RECEIVE_NAME] = $this->receiveName;
        $result [self::RECEIVE_ACCOUNT] = $this->receiveAccount;
        $result [self::DOCUMENT_NUMBER] = $this->documentNumber;
        $result [self::DOCUMENT_DATE] = $this->documentDate;

        $result [self::INCOME_CURRENCY_ID] = $this->incomeCurrency;
        $result [self::OUTCOME_CURRENCY_ID] = $this->outcomeCurrency;
        $result [self::TRANSFER_STATUS_ID] = $this->transferStatus;

        $result [self::STATUS_COMMENT] = $this->statusComment;
        $result [self::STATUS_TIME] = $this->statusTime;
        $result [self::AWAIT_NAME] = $this->awaitName;
        $result [self::AWAIT_ACCOUNT] = $this->awaitAccount;
        $result [self::FEE] = $this->fee;
        $result [self::PROCEED_ACCOUNT] = $this->proceedAccount;
        $result [self::PROCEED_NAME] = $this->proceedName;
        $result [self::BODY] = $this->body;
        $result [self::PLACEMENT_DATE] = $this->placementDate;
        $result [self::COST] = $this->cost;


        return $result;
    }

    public function save(): bool
    {
        $input = new InputFormatter(IDbFormatter::POSTGRES);

        $incomeAmountString = is_null($this->incomeAmount)
            ? null
            : $input->toDoublePrecision(floatval($this->incomeAmount));

        $outcomeAmountString = is_null($this->outcomeAmount)
            ? null
            : $input->toDoublePrecision(floatval($this->outcomeAmount));

        $feeString = is_null($this->fee)
            ? null
            : $input->toDoublePrecision(floatval($this->fee));

        $bodyString = is_null($this->body)
            ? null
            : $input->toDoublePrecision(floatval($this->body));

        $costString = is_null($this->cost)
            ? null
            : $input->toDoublePrecision(floatval($this->cost));

        $documentDateString = is_null($this->documentDate)
            ? null
            : $input->toTimestampWithTimeZone($this->documentDate);

        $statusTimeString = is_null($this->statusTime)
            ? null
            : $input->toTimestampWithTimeZone($this->statusTime);

        $placementDateString = is_null($this->placementDate)
            ? null
            : $input->toTimestampWithTimeZone($this->placementDate);

        $id = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_STR);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

        $incomeAmount = SqlHandler::setBindParameter(':INCOME_AMOUNT', $incomeAmountString, \PDO::PARAM_STR);
        $outcomeAmount = SqlHandler::setBindParameter(':OUTCOME_AMOUNT', $outcomeAmountString, \PDO::PARAM_STR);
        $reportEmail = SqlHandler::setBindParameter(':REPORT_EMAIL', $this->reportEmail, \PDO::PARAM_STR);
        $transferName = SqlHandler::setBindParameter(':TRANSFER_NAME', $this->transferName, \PDO::PARAM_STR);
        $transferAccount = SqlHandler::setBindParameter(':TRANSFER_ACCOUNT', $this->transferAccount, \PDO::PARAM_STR);
        $receiveName = SqlHandler::setBindParameter(':RECEIVE_NAME', $this->receiveName, \PDO::PARAM_STR);
        $receiveAccount = SqlHandler::setBindParameter(':RECEIVE_ACCOUNT', $this->receiveAccount, \PDO::PARAM_STR);
        $documentNumber = SqlHandler::setBindParameter(':DOCUMENT_NUMBER', $this->documentNumber, \PDO::PARAM_STR);
        $documentDate = SqlHandler::setBindParameter(':DOCUMENT_DATE', $documentDateString, \PDO::PARAM_STR);
        $incomeCurrency = SqlHandler::setBindParameter(':INCOME_CURRENCY_ID', $this->incomeCurrency, \PDO::PARAM_INT);
        $outcomeCurrency = SqlHandler::setBindParameter(':OUTCOME_CURRENCY_ID', $this->outcomeCurrency, \PDO::PARAM_INT);
        $status = SqlHandler::setBindParameter(':STATUS_ID', $this->transferStatus, \PDO::PARAM_INT);
        $statusComment = SqlHandler::setBindParameter(':STATUS_COMMENT', $this->statusComment, \PDO::PARAM_STR);
        $statusTime = SqlHandler::setBindParameter(':STATUS_TIME', $statusTimeString, \PDO::PARAM_STR);
        $awaitName = SqlHandler::setBindParameter(':AWAIT_NAME', $this->awaitName, \PDO::PARAM_STR);
        $awaitAccount = SqlHandler::setBindParameter(':AWAIT_ACCOUNT', $this->awaitAccount, \PDO::PARAM_STR);
        $fee = SqlHandler::setBindParameter(':FEE', $feeString, \PDO::PARAM_STR);
        $proceedAccount = SqlHandler::setBindParameter(':PROCEED_ACCOUNT', $this->proceedAccount, \PDO::PARAM_STR);
        $proceedName = SqlHandler::setBindParameter(':PROCEED_NAME', $this->proceedName, \PDO::PARAM_STR);
        $body = SqlHandler::setBindParameter(':BODY', $bodyString, \PDO::PARAM_STR);
        $placementDate = SqlHandler::setBindParameter(':PLACEMENT_DATE', $placementDateString, \PDO::PARAM_STR);
        $cost = SqlHandler::setBindParameter(':COST', $costString, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $incomeAmount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $outcomeAmount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $reportEmail;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $transferName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $transferAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $receiveName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $receiveAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $documentNumber;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $documentDate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $incomeCurrency;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $outcomeCurrency;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $status;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $statusComment;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $statusTime;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHidden;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $id;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $awaitName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $awaitAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $fee;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $proceedAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $proceedName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $body;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $placementDate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $cost;

        $isHiddenFilterValue = $input->toInteger(self::DEFINE_AS_NOT_HIDDEN);
        $isHiddenFilter = SqlHandler::setBindParameter(':HIDDEN_FILTER', $isHiddenFilterValue, \PDO::PARAM_INT);
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenFilter;

        $output = new OutputFormatter(IDbFormatter::POSTGRES);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::INCOME_AMOUNT . ' = ' . $input->castFloat($incomeAmount[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::OUTCOME_AMOUNT . ' = ' . $input->castFloat($outcomeAmount[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::REPORT_EMAIL . ' = ' . $reportEmail[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TRANSFER_NAME . ' = ' . $transferName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TRANSFER_ACCOUNT . ' = ' . $transferAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::RECEIVE_NAME . ' = ' . $receiveName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::RECEIVE_ACCOUNT . ' = ' . $receiveAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::DOCUMENT_NUMBER . ' = ' . $documentNumber[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::DOCUMENT_DATE . ' = ' . $input->castTimestamp($documentDate[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::INCOME_CURRENCY_ID . ' = ' . $incomeCurrency[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::OUTCOME_CURRENCY_ID . ' = ' . $outcomeCurrency[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TRANSFER_STATUS_ID . ' = ' . $status[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_COMMENT . ' = ' . $statusComment[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_TIME . ' = ' . $input->castTimestamp($statusTime[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::AWAIT_NAME . ' = ' . $awaitName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AWAIT_ACCOUNT . ' = ' . $awaitAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::FEE . ' = ' . $input->castFloat($fee[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::PROCEED_ACCOUNT . ' = ' . $proceedAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::PROCEED_NAME . ' = ' . $proceedName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::BODY . ' = ' . $input->castFloat($body[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::PLACEMENT_DATE . ' = ' . $input->castTimestamp($placementDate[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::COST . ' = ' . $input->castFloat($cost[ISqlHandler::PLACEHOLDER])

            . ' WHERE '
            . self::IS_HIDDEN . ' = ' . $isHiddenFilter[ISqlHandler::PLACEHOLDER]
            . ' AND ' . self::DOCUMENT_NUMBER . ' = ' . $documentNumber[ISqlHandler::PLACEHOLDER]
            . ' AND ' . self::DOCUMENT_DATE . ' = ' . $input->castTimestamp($documentDate[ISqlHandler::PLACEHOLDER])
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
            . ' , ' . $output->castTimestampToString(self::DOCUMENT_DATE) . ' AS ' . self::DOCUMENT_DATE
            . ' , ' . self::INCOME_CURRENCY_ID
            . ' , ' . self::OUTCOME_CURRENCY_ID
            . ' , ' . self::TRANSFER_STATUS_ID
            . ' , ' . self::STATUS_COMMENT
            . ' , ' . $output->castTimestampToString(self::STATUS_TIME) . ' AS ' . self::STATUS_TIME
            . ' , ' . self::AWAIT_NAME
            . ' , ' . self::AWAIT_ACCOUNT
            . ' , ' . self::FEE
            . ' , ' . self::PROCEED_ACCOUNT
            . ' , ' . self::PROCEED_NAME
            . ' , ' . self::BODY
            . ' , ' . $output->castTimestampToString(self::PLACEMENT_DATE) . ' AS ' . self::PLACEMENT_DATE
            . ' , ' . self::COST
            . ';';

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

        $output = new OutputFormatter(IDbFormatter::POSTGRES);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
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
            . ' , ' . $output->castTimestampToString(self::DOCUMENT_DATE) . ' AS ' . self::DOCUMENT_DATE
            . ' , ' . self::INCOME_CURRENCY_ID
            . ' , ' . self::OUTCOME_CURRENCY_ID
            . ' , ' . self::TRANSFER_STATUS_ID
            . ' , ' . self::STATUS_COMMENT
            . ' , ' . $output->castTimestampToString(self::STATUS_TIME) . ' AS ' . self::STATUS_TIME
            . ' , ' . self::AWAIT_NAME
            . ' , ' . self::AWAIT_ACCOUNT
            . ' , ' . self::FEE
            . ' , ' . self::PROCEED_ACCOUNT
            . ' , ' . self::PROCEED_NAME
            . ' , ' . self::BODY
            . ' , ' . $output->castTimestampToString(self::PLACEMENT_DATE) . ' AS ' . self::PLACEMENT_DATE
            . ' , ' . self::COST
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
     * @param array $namedValues массив значений
     * @return bool успех выполнения
     */
    public function setByNamedValue(array $namedValues): bool
    {

        $result = parent::setByNamedValue($namedValues);

        $this->incomeAmount = SqlHandler::getDoublePrecisionValue(self::INCOME_AMOUNT, $namedValues);
        $this->outcomeAmount = SqlHandler::getDoublePrecisionValue(self::OUTCOME_AMOUNT, $namedValues);
        $this->reportEmail = SqlHandler::getTextValue(self::REPORT_EMAIL, $namedValues);
        $this->transferName = SqlHandler::getTextValue(self::TRANSFER_NAME, $namedValues);
        $this->transferAccount = SqlHandler::getTextValue(self::TRANSFER_ACCOUNT, $namedValues);
        $this->receiveName = SqlHandler::getTextValue(self::RECEIVE_NAME, $namedValues);
        $this->receiveAccount = SqlHandler::getTextValue(self::RECEIVE_ACCOUNT, $namedValues);
        $this->documentNumber = SqlHandler::getTextValue(self::DOCUMENT_NUMBER, $namedValues);

        $this->documentDate = SqlHandler::getTimestampValue(self::DOCUMENT_DATE, $namedValues);

        $this->incomeCurrency = SqlHandler::getIntegerKey(self::INCOME_CURRENCY_ID, $namedValues);
        $this->outcomeCurrency = SqlHandler::getIntegerKey(self::OUTCOME_CURRENCY_ID, $namedValues);
        $this->transferStatus = SqlHandler::getIntegerKey(self::TRANSFER_STATUS_ID, $namedValues);

        $this->statusComment = SqlHandler::getTextValue(self::STATUS_COMMENT, $namedValues);

        $this->statusTime = SqlHandler::getTimestampValue(self::STATUS_TIME, $namedValues);

        $this->awaitName = SqlHandler::getTextValue(self::AWAIT_NAME, $namedValues);
        $this->awaitAccount = SqlHandler::getTextValue(self::AWAIT_ACCOUNT, $namedValues);
        $this->fee = SqlHandler::getDoublePrecisionValue(self::FEE, $namedValues);
        $this->proceedAccount = SqlHandler::getTextValue(self::PROCEED_ACCOUNT, $namedValues);
        $this->proceedName = SqlHandler::getTextValue(self::PROCEED_NAME, $namedValues);
        $this->body = SqlHandler::getDoublePrecisionValue(self::BODY, $namedValues);

        $this->placementDate = SqlHandler::getTimestampValue(self::PLACEMENT_DATE, $namedValues);

        $this->cost = SqlHandler::getDoublePrecisionValue(self::COST, $namedValues);


        return $result;
    }

    /** Обновить данные в БД
     * @return bool успех выполнения
     */
    protected function updateEntity(): bool
    {
        $formatter = new InputFormatter(InputFormatter::POSTGRES);

        $incomeAmountString = is_null($this->incomeAmount)
            ? null
            : $formatter->toDoublePrecision(floatval($this->incomeAmount));

        $outcomeAmountString = is_null($this->outcomeAmount)
            ? null
            : $formatter->toDoublePrecision(floatval($this->outcomeAmount));

        $feeString = is_null($this->fee)
            ? null
            : $formatter->toDoublePrecision(floatval($this->fee));

        $bodyString = is_null($this->body)
            ? null
            : $formatter->toDoublePrecision(floatval($this->body));

        $costString = is_null($this->cost)
            ? null
            : $formatter->toDoublePrecision(floatval($this->cost));

        $documentDateString = is_null($this->documentDate)
            ? null
            : $formatter->toTimestampWithTimeZone($this->documentDate);

        $statusTimeString = is_null($this->statusTime)
            ? null
            : $formatter->toTimestampWithTimeZone($this->statusTime);

        $placementDateString = is_null($this->placementDate)
            ? null
            : $formatter->toTimestampWithTimeZone($this->placementDate);

        $id = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_STR);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

        $incomeAmount = SqlHandler::setBindParameter(':INCOME_AMOUNT', $incomeAmountString, \PDO::PARAM_STR);
        $outcomeAmount = SqlHandler::setBindParameter(':OUTCOME_AMOUNT', $outcomeAmountString, \PDO::PARAM_STR);
        $reportEmail = SqlHandler::setBindParameter(':REPORT_EMAIL', $this->reportEmail, \PDO::PARAM_STR);
        $transferName = SqlHandler::setBindParameter(':TRANSFER_NAME', $this->transferName, \PDO::PARAM_STR);
        $transferAccount = SqlHandler::setBindParameter(':TRANSFER_ACCOUNT', $this->transferAccount, \PDO::PARAM_STR);
        $receiveName = SqlHandler::setBindParameter(':RECEIVE_NAME', $this->receiveName, \PDO::PARAM_STR);
        $receiveAccount = SqlHandler::setBindParameter(':RECEIVE_ACCOUNT', $this->receiveAccount, \PDO::PARAM_STR);
        $documentNumber = SqlHandler::setBindParameter(':DOCUMENT_NUMBER', $this->documentNumber, \PDO::PARAM_STR);
        $documentDate = SqlHandler::setBindParameter(':DOCUMENT_DATE', $documentDateString, \PDO::PARAM_STR);
        $incomeCurrency = SqlHandler::setBindParameter(':INCOME_CURRENCY_ID', $this->incomeCurrency, \PDO::PARAM_INT);
        $outcomeCurrency = SqlHandler::setBindParameter(':OUTCOME_CURRENCY_ID', $this->outcomeCurrency, \PDO::PARAM_INT);
        $status = SqlHandler::setBindParameter(':STATUS_ID', $this->transferStatus, \PDO::PARAM_INT);
        $statusComment = SqlHandler::setBindParameter(':STATUS_COMMENT', $this->statusComment, \PDO::PARAM_STR);
        $statusTime = SqlHandler::setBindParameter(':STATUS_TIME', $statusTimeString, \PDO::PARAM_STR);
        $awaitName = SqlHandler::setBindParameter(':AWAIT_NAME', $this->awaitName, \PDO::PARAM_STR);
        $awaitAccount = SqlHandler::setBindParameter(':AWAIT_ACCOUNT', $this->awaitAccount, \PDO::PARAM_STR);
        $fee = SqlHandler::setBindParameter(':FEE', $feeString, \PDO::PARAM_STR);
        $proceedAccount = SqlHandler::setBindParameter(':PROCEED_ACCOUNT', $this->proceedAccount, \PDO::PARAM_STR);
        $proceedName = SqlHandler::setBindParameter(':PROCEED_NAME', $this->proceedName, \PDO::PARAM_STR);
        $body = SqlHandler::setBindParameter(':BODY', $bodyString, \PDO::PARAM_STR);
        $placementDate = SqlHandler::setBindParameter(':PLACEMENT_DATE', $placementDateString, \PDO::PARAM_STR);
        $cost = SqlHandler::setBindParameter(':COST', $costString, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $incomeAmount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $outcomeAmount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $reportEmail;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $transferName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $transferAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $receiveName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $receiveAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $documentNumber;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $documentDate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $incomeCurrency;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $outcomeCurrency;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $status;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $statusComment;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $statusTime;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHidden;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $id;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $awaitName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $awaitAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $fee;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $proceedAccount;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $proceedName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $body;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $placementDate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $cost;

        $output = new OutputFormatter(IDbFormatter::POSTGRES);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::INCOME_AMOUNT . ' = ' . $formatter->castFloat($incomeAmount[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::OUTCOME_AMOUNT . ' = ' . $formatter->castFloat($outcomeAmount[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::REPORT_EMAIL . ' = ' . $reportEmail[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TRANSFER_NAME . ' = ' . $transferName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TRANSFER_ACCOUNT . ' = ' . $transferAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::RECEIVE_NAME . ' = ' . $receiveName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::RECEIVE_ACCOUNT . ' = ' . $receiveAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::DOCUMENT_NUMBER . ' = ' . $documentNumber[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::DOCUMENT_DATE . ' = ' . $formatter->castTimestamp($documentDate[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::INCOME_CURRENCY_ID . ' = ' . $incomeCurrency[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::OUTCOME_CURRENCY_ID . ' = ' . $outcomeCurrency[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TRANSFER_STATUS_ID . ' = ' . $status[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_COMMENT . ' = ' . $statusComment[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::STATUS_TIME . ' = ' . $formatter->castTimestamp($statusTime[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AWAIT_NAME . ' = ' . $awaitName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AWAIT_ACCOUNT . ' = ' . $awaitAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::FEE . ' = ' . $formatter->castFloat($fee[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::PROCEED_ACCOUNT . ' = ' . $proceedAccount[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::PROCEED_NAME . ' = ' . $proceedName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::BODY . ' = ' . $formatter->castFloat($body[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::PLACEMENT_DATE . ' = ' . $formatter->castTimestamp($placementDate[ISqlHandler::PLACEHOLDER])
            . ' , ' . self::COST . ' = ' . $formatter->castFloat($cost[ISqlHandler::PLACEHOLDER])
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
            . ' , ' . $output->castTimestampToString(self::DOCUMENT_DATE) . ' AS ' . self::DOCUMENT_DATE
            . ' , ' . self::INCOME_CURRENCY_ID
            . ' , ' . self::OUTCOME_CURRENCY_ID
            . ' , ' . self::TRANSFER_STATUS_ID
            . ' , ' . self::STATUS_COMMENT
            . ' , ' . $output->castTimestampToString(self::STATUS_TIME) . ' AS ' . self::STATUS_TIME
            . ' , ' . self::AWAIT_NAME
            . ' , ' . self::AWAIT_ACCOUNT
            . ' , ' . self::FEE
            . ' , ' . self::PROCEED_ACCOUNT
            . ' , ' . self::PROCEED_NAME
            . ' , ' . self::BODY
            . ' , ' . $output->castTimestampToString(self::PLACEMENT_DATE) . ' AS ' . self::PLACEMENT_DATE
            . ' , ' . self::COST
            . ';';

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record !== ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
    }
}
