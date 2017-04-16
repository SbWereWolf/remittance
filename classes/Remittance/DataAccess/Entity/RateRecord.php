<?php

namespace Remittance\DataAccess\Entity;


use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;


class RateRecord extends Entity
{

    /** @var string имя таблицы для хранения сущности */
    const TABLE_NAME = 'rate';

    /** @var string колонка для внешнего ключа ссылки на эту таблицу */
    const EXTERNAL_ID = 'rate_id';

    const SOURCE_CURRENCY = 'source_currency_id';
    const TARGET_CURRENCY = 'target_currency_id';
    const EXCHANGE_RATE = 'exchange_rate';
    const FEE = 'fee';
    const EFFECTIVE_RATE = 'effective_rate';
    const IS_DEFAULT = 'is_default';

    public $sourceCurrencyId = 0;
    public $targetCurrencyId = 0;
    public $exchangeRate = 0;
    public $fee = 0;
    public $effectiveRate = 0;
    public $isDefault = 0;

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    protected $classname = self::class;

    public static function adopt($object): RateRecord
    {

        return $object;

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
            . ' ,' . self::IS_HIDDEN
            . ' ,' . self::SOURCE_CURRENCY
            . ' ,' . self::TARGET_CURRENCY
            . ' ,' . self::EXCHANGE_RATE
            . ' ,' . self::FEE
            . ' ,' . self::EFFECTIVE_RATE
            . ' ,' . self::IS_DEFAULT
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

        $this->sourceCurrencyId = intval(SqlHandler::setIfExists(self::SOURCE_CURRENCY, $namedValue));
        $this->targetCurrencyId = intval(SqlHandler::setIfExists(self::TARGET_CURRENCY, $namedValue));
        $this->exchangeRate = floatval(SqlHandler::setIfExists(self::EXCHANGE_RATE, $namedValue));
        $this->fee = floatval(SqlHandler::setIfExists(self::FEE, $namedValue));
        $this->effectiveRate = floatval(SqlHandler::setIfExists(self::EFFECTIVE_RATE, $namedValue));
        $this->isDefault = floatval(SqlHandler::setIfExists(self::IS_DEFAULT, $namedValue));

        return $result;
    }

    /** Формирует массив из свойств экземпляра
     * @return array массив свойств экземпляра
     */
    public function toEntity(): array
    {
        parent::toEntity();

        $result [self::SOURCE_CURRENCY] = intval($this->sourceCurrencyId);
        $result [self::TARGET_CURRENCY] = intval($this->targetCurrencyId);
        $result [self::EXCHANGE_RATE] = floatval($this->exchangeRate);
        $result [self::FEE] = floatval($this->fee);
        $result [self::EFFECTIVE_RATE] = floatval($this->effectiveRate);
        $result [self::IS_DEFAULT] = intval($this->isDefault);

        return $result;
    }

    /** Обновить данные в БД
     * @return bool успех выполнения
     */
    protected function updateEntity(): bool
    {
        $id = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

        $sourceCurrencyId = SqlHandler::setBindParameter(':SOURCE_CURRENCY', $this->sourceCurrencyId, \PDO::PARAM_INT);
        $targetCurrencyId = SqlHandler::setBindParameter(':TARGET_CURRENCY', $this->targetCurrencyId, \PDO::PARAM_INT);
        $exchangeRate = SqlHandler::setBindParameter(':EXCHANGE_RATE', $this->exchangeRate, \PDO::PARAM_STR);
        $fee = SqlHandler::setBindParameter(':FEE', $this->fee, \PDO::PARAM_STR);
        $effectiveRate = SqlHandler::setBindParameter(':EFFECTIVE_RATE', $this->effectiveRate, \PDO::PARAM_STR);
        $isDefault = SqlHandler::setBindParameter(':IS_DEFAULT', $this->isDefault, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::SOURCE_CURRENCY . ' = ' . $sourceCurrencyId[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TARGET_CURRENCY . ' = ' . $targetCurrencyId[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::EXCHANGE_RATE . ' = CAST(' . $exchangeRate[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::FEE . ' = CAST(' . $fee[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::EFFECTIVE_RATE . ' = CAST(' . $effectiveRate[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::IS_DEFAULT . ' = ' . $isDefault[ISqlHandler::PLACEHOLDER]
            . ' WHERE '
            . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::SOURCE_CURRENCY
            . ' , ' . self::TARGET_CURRENCY
            . ' , ' . self::EXCHANGE_RATE
            . ' , ' . self::FEE
            . ' , ' . self::EFFECTIVE_RATE
            . ' , ' . self::IS_DEFAULT
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $sourceCurrencyId;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $targetCurrencyId;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $exchangeRate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $fee;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $effectiveRate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isDefault;
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
