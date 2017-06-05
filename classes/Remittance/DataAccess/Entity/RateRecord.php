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

    /** @var string значение для поднятого флага "использовать по умолчанию" */
    const DEFINE_AS_DEFAULT = true;
    /** @var string значение для снятого флага "использовать по умолчанию" */
    const DEFINE_AS_NOT_DEFAULT = false;
    /** @var string значение по умолчанию для флага "использовать по умолчанию" */
    const DEFAULT_IS_DEFAULT = self::DEFINE_AS_NOT_DEFAULT;

    const SOURCE_CURRENCY = 'source_currency_id';
    const TARGET_CURRENCY = 'target_currency_id';
    const RATIO = 'exchange_rate';
    const FEE = 'fee';
    const IS_DEFAULT = 'is_default';

    public $sourceCurrencyId = 0;
    public $targetCurrencyId = 0;
    public $ratio = 0;
    public $fee = 0;
    public $isDefault = 0;

    /** @var string имя таблицы для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    protected $classname = self::class;

    public static function adopt($object): RateRecord
    {

        return $object;

    }

    public function save(): bool
    {
        $exchangeRate = SqlHandler::setBindParameter(':EXCHANGE_RATE', $this->ratio, \PDO::PARAM_STR);
        $fee = SqlHandler::setBindParameter(':FEE', $this->fee, \PDO::PARAM_STR);
        //$isDefault = SqlHandler::setBindParameter(':IS_DEFAULT', $this->isDefault, \PDO::PARAM_INT);
        $sourceCurrency = SqlHandler::setBindParameter(':SOURCE_CURRENCY', $this->sourceCurrencyId, \PDO::PARAM_STR);
        $targetCurrency = SqlHandler::setBindParameter(':TARGET_CURRENCY', $this->targetCurrencyId, \PDO::PARAM_STR);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::RATIO . ' = CAST(' . $exchangeRate[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::FEE . ' = CAST(' . $fee[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            //. ' , ' . self::IS_DEFAULT . ' = ' . $isDefault[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
            . ' WHERE '
            . self::SOURCE_CURRENCY . ' = ' . $sourceCurrency[ISqlHandler::PLACEHOLDER]
            . ' AND ' . self::TARGET_CURRENCY . ' = ' . $targetCurrency[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::RATIO
            . ' , ' . self::FEE
            . ' , ' . self::IS_DEFAULT
            . ' , ' . self::SOURCE_CURRENCY
            . ' , ' . self::TARGET_CURRENCY
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $exchangeRate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $fee;
        //$arguments[ISqlHandler::QUERY_PARAMETER][] = $isDefault;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $sourceCurrency;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $targetCurrency;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHidden;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record !== ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
    }

    public function setDefault()
    {
        $defineAsDefault = SqlHandler::setBindParameter(':IS_DEFAULT', self::DEFINE_AS_DEFAULT, \PDO::PARAM_INT);
        $sourceCurrency = SqlHandler::setBindParameter(':SOURCE_CURRENCY', $this->sourceCurrencyId, \PDO::PARAM_STR);
        $targetCurrency = SqlHandler::setBindParameter(':TARGET_CURRENCY', $this->targetCurrencyId, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::IS_DEFAULT . ' = ' . $defineAsDefault[ISqlHandler::PLACEHOLDER]
            . ' WHERE '
            . self::SOURCE_CURRENCY . ' = ' . $sourceCurrency[ISqlHandler::PLACEHOLDER]
            . ' AND ' . self::TARGET_CURRENCY . ' = ' . $targetCurrency[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::RATIO
            . ' , ' . self::FEE
            . ' , ' . self::IS_DEFAULT
            . ' , ' . self::SOURCE_CURRENCY
            . ' , ' . self::TARGET_CURRENCY
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $defineAsDefault;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $sourceCurrency;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $targetCurrency;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = false;
        if ($record !== ISqlHandler::EMPTY_ARRAY) {
            $result = $this->setByNamedValue($record);
        }

        return $result;
    }

    public function unsetDefault()
    {
        $defineAsNotDefault = SqlHandler::setBindParameter(':UNSET_DEFAULT', self::DEFINE_AS_NOT_DEFAULT, \PDO::PARAM_INT);
        $defineAsDefault = SqlHandler::setBindParameter(':SET_DEFAULT', self::DEFINE_AS_DEFAULT, \PDO::PARAM_INT);
        $sourceCurrency = SqlHandler::setBindParameter(':SOURCE_CURRENCY', $this->sourceCurrencyId, \PDO::PARAM_STR);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::IS_DEFAULT . ' = ' . $defineAsNotDefault[ISqlHandler::PLACEHOLDER]
            . ' WHERE '
            . self::SOURCE_CURRENCY . ' = ' . $sourceCurrency[ISqlHandler::PLACEHOLDER]
            . ' AND ' . self::IS_DEFAULT . ' = ' . $defineAsDefault[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::RATIO
            . ' , ' . self::FEE
            . ' , ' . self::IS_DEFAULT
            . ' , ' . self::SOURCE_CURRENCY
            . ' , ' . self::TARGET_CURRENCY
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $defineAsNotDefault;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $defineAsDefault;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $sourceCurrency;

        $record = SqlHandler::writeOneRecord($arguments);

        $result = $record !== ISqlHandler::EMPTY_ARRAY;

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
            . ' ,' . self::IS_HIDDEN
            . ' ,' . self::SOURCE_CURRENCY
            . ' ,' . self::TARGET_CURRENCY
            . ' ,' . self::RATIO
            . ' ,' . self::FEE
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
        $this->ratio = floatval(SqlHandler::setIfExists(self::RATIO, $namedValue));
        $this->fee = floatval(SqlHandler::setIfExists(self::FEE, $namedValue));
        $this->isDefault = boolval(SqlHandler::setIfExists(self::IS_DEFAULT, $namedValue));

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
        $result [self::RATIO] = floatval($this->ratio);
        $result [self::FEE] = floatval($this->fee);
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
        $exchangeRate = SqlHandler::setBindParameter(':EXCHANGE_RATE', $this->ratio, \PDO::PARAM_STR);
        $fee = SqlHandler::setBindParameter(':FEE', $this->fee, \PDO::PARAM_STR);
        $isDefault = SqlHandler::setBindParameter(':IS_DEFAULT', $this->isDefault, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            ' UPDATE '
            . $this->tablename
            . ' SET '
            . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::SOURCE_CURRENCY . ' = ' . $sourceCurrencyId[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::TARGET_CURRENCY . ' = ' . $targetCurrencyId[ISqlHandler::PLACEHOLDER]
            . ' , ' . self::RATIO . ' = CAST(' . $exchangeRate[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::FEE . ' = CAST(' . $fee[ISqlHandler::PLACEHOLDER] . ' AS DOUBLE PRECISION)'
            . ' , ' . self::IS_DEFAULT . ' = ' . $isDefault[ISqlHandler::PLACEHOLDER]
            . ' WHERE '
            . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER]
            . ' RETURNING '
            . self::ID
            . ' , ' . self::IS_HIDDEN
            . ' , ' . self::SOURCE_CURRENCY
            . ' , ' . self::TARGET_CURRENCY
            . ' , ' . self::RATIO
            . ' , ' . self::FEE
            . ' , ' . self::EFFECTIVE_RATE
            . ' , ' . self::IS_DEFAULT
            . ';';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $sourceCurrencyId;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $targetCurrencyId;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $exchangeRate;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $fee;
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
