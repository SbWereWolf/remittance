<?php

namespace Remittance\DataAccess\Entity;


use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;


class VolumeRecord extends Entity
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = 'volume';

    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'volume_id';

    const CURRENCY_ID = 'currency_id';
    const AMOUNT = 'volume';
    const RESERVE = 'reserve';
    const ACCOUNT_NAME = 'account_name';
    const ACCOUNT_NUMBER = 'account_number';
    const LIMITATION = 'limitation';
    const TOTAL = 'total';

    public $currencyId = 0;
    public $amount = 0;
    public $reserve = 0;
    public $accountName = '';
    public $accountNumber = '';
    public $limitation = 0;
    public $total = 0;

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    protected $classname = self::class;

    public static function adopt($object): VolumeRecord
    {
        return $object;
    }

    public function save(): bool
    {
        $reserve = SqlHandler::setBindParameter(':RESERVE', $this->reserve, \PDO::PARAM_STR);
        $accountName = SqlHandler::setBindParameter(':ACCOUNT_NAME', $this->accountName, \PDO::PARAM_STR);
        $accountNumber = SqlHandler::setBindParameter(':ACCOUNT_NUMBER', $this->accountNumber, \PDO::PARAM_STR);
        $limitation = SqlHandler::setBindParameter(':LIMITATION', $this->limitation, \PDO::PARAM_STR);
        $total = SqlHandler::setBindParameter(':TOTAL', $this->total, \PDO::PARAM_STR);
        $currencyId = SqlHandler::setBindParameter(':CURRENCY_ID', $this->currencyId, \PDO::PARAM_INT);
        $volume = SqlHandler::setBindParameter(':VOLUME', $this->amount, \PDO::PARAM_STR);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::RESERVE . ' = CAST(' . $reserve[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::ACCOUNT_NAME . ' = ' . $accountName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::ACCOUNT_NUMBER . ' = ' . $accountNumber[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AMOUNT . ' = CAST(' . $volume[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::LIMITATION . ' = CAST(' . $limitation[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::TOTAL . ' = CAST(' . $total[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
            . ' WHERE '
            . self::CURRENCY_ID . ' = ' . $currencyId[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::CURRENCY_ID
            . ' , ' . self::AMOUNT
            . ' , ' . self::RESERVE
            . ' , ' . self::ACCOUNT_NAME
            . ' , ' . self::ACCOUNT_NUMBER
            . ' , ' . self::LIMITATION
            . ' , ' . self::TOTAL
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $reserve;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $accountName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $accountNumber;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $limitation;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $total;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $currencyId;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $volume;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHidden;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record !== ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
    }

    public function income(float $income)
    {

        $id = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
        $incomeParameter = SqlHandler::setBindParameter(':INCOME', $income, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::AMOUNT . ' =' . self::AMOUNT . '  + CAST(' . $incomeParameter[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' WHERE '
            . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::CURRENCY_ID
            . ' , ' . self::AMOUNT
            . ' , ' . self::RESERVE
            . ' , ' . self::ACCOUNT_NAME
            . ' , ' . self::ACCOUNT_NUMBER
            . ' , ' . self::LIMITATION
            . ' , ' . self::TOTAL
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $id;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $incomeParameter;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record !== ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;

    }

    public function outcome(float $outcome)
    {
        $id = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
        $outcomeParameter = SqlHandler::setBindParameter(':INCOME', $outcome, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::AMOUNT . ' =' . self::AMOUNT . '  - CAST(' . $outcomeParameter[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::RESERVE . ' =' . self::RESERVE . '  - CAST(' . $outcomeParameter[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::TOTAL . ' =' . self::TOTAL . '  + CAST(' . $outcomeParameter[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' WHERE '
            . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::CURRENCY_ID
            . ' , ' . self::AMOUNT
            . ' , ' . self::RESERVE
            . ' , ' . self::ACCOUNT_NAME
            . ' , ' . self::ACCOUNT_NUMBER
            . ' , ' . self::LIMITATION
            . ' , ' . self::TOTAL
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $id;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $outcomeParameter;

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
        $idParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT '
            . self::ID
            . ' ,' . self::IS_HIDDEN
            . ' ,' . self::CURRENCY_ID
            . ' ,' . self::AMOUNT
            . ' ,' . self::RESERVE
            . ' ,' . self::ACCOUNT_NAME
            . ' ,' . self::ACCOUNT_NUMBER
            . ' ,' . self::LIMITATION
            . ' ,' . self::TOTAL
            . ' FROM '
            . $this->tablename
            . ' WHERE '
            . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

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

        $this->currencyId = intval(SqlHandler::setIfExists(self::CURRENCY_ID, $namedValues));
        $this->amount = floatval(SqlHandler::setIfExists(self::AMOUNT, $namedValues));
        $this->reserve = floatval(SqlHandler::setIfExists(self::RESERVE, $namedValues));
        $this->accountName = strval(SqlHandler::setIfExists(self::ACCOUNT_NAME, $namedValues));
        $this->accountNumber = strval(SqlHandler::setIfExists(self::ACCOUNT_NUMBER, $namedValues));
        $this->limitation = floatval(SqlHandler::setIfExists(self::LIMITATION, $namedValues));
        $this->total = floatval(SqlHandler::setIfExists(self::TOTAL, $namedValues));

        return $result;
    }

    /** Формирует массив из свойств экземпляра
     * @return array массив свойств экземпляра
     */
    public function toEntity(): array
    {
        parent::toEntity();

        $result [self::CURRENCY_ID] = intval($this->currencyId);
        $result [self::AMOUNT] = floatval($this->amount);
        $result [self::RESERVE] = floatval($this->reserve);
        $result [self::ACCOUNT_NAME] = strval($this->accountName);
        $result [self::ACCOUNT_NUMBER] = strval($this->accountNumber);
        $result [self::LIMITATION] = floatval($this->limitation);
        $result [self::TOTAL] = floatval($this->total);

        return $result;
    }

    /** Обновить данные в БД
     * @return bool успех выполнения
     */
    protected function updateEntity(): bool
    {
        $id = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

        $currencyId = SqlHandler::setBindParameter(':CURRENCY_ID', $this->currencyId, \PDO::PARAM_INT);
        $volume = SqlHandler::setBindParameter(':VOLUME', $this->amount, \PDO::PARAM_STR);
        $reserve = SqlHandler::setBindParameter(':RESERVE', $this->reserve, \PDO::PARAM_STR);
        $accountName = SqlHandler::setBindParameter(':ACCOUNT_NAME', $this->accountName, \PDO::PARAM_STR);
        $accountNumber = SqlHandler::setBindParameter(':ACCOUNT_NUMBER', $this->accountNumber, \PDO::PARAM_STR);
        $limitation = SqlHandler::setBindParameter(':LIMITATION', $this->limitation, \PDO::PARAM_STR);
        $total = SqlHandler::setBindParameter(':TOTAL', $this->total, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::CURRENCY_ID . ' = ' . $currencyId[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::AMOUNT . ' = CAST(' . $volume[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::RESERVE . ' = CAST(' . $reserve[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::ACCOUNT_NAME . ' = ' . $accountName[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::ACCOUNT_NUMBER . ' = ' . $accountNumber[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::LIMITATION . ' = CAST(' . $limitation[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::TOTAL . ' = CAST(' . $total[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' WHERE '
            . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::CURRENCY_ID
            . ' , ' . self::AMOUNT
            . ' , ' . self::RESERVE
            . ' , ' . self::ACCOUNT_NAME
            . ' , ' . self::ACCOUNT_NUMBER
            . ' , ' . self::LIMITATION
            . ' , ' . self::TOTAL
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $currencyId;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $volume;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $reserve;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $accountName;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $accountNumber;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $limitation;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $total;
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
