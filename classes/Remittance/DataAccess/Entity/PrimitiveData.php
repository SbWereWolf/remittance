<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.01.2017
 * Time: 1:40
 */

namespace Remittance\DataAccess\Entity;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;

class PrimitiveData extends Record implements IPrimitiveData
{

    /** @var string константа значение не задано для значимых типов */
    const EMPTY_VALUE = ICommon::EMPTY_VALUE;
    /** @var null константа значение не задано для ссылочных типов */
    const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
    /** @var array константа значение не задано для массивов */
    const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

    /** @var string имя таблицы БД для хранения сущности */
    const TABLE_NAME = 'primitive_data';
    public $isHidden = false;
    /** @var string имя таблицы БД для хранения сущности */
    protected $tablename = self::TABLE_NAME;

    public function mutateEntity():bool
    {
        $result = false;
        return $result;
    }

    /** Скрыть сущность
     * @return bool успех выполнения
     */
    public function hideEntity():bool
    {

        $id = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
        $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', self::DEFINE_AS_HIDDEN, \PDO::PARAM_INT);

        $arguments[SqlHandler::QUERY_TEXT] = '
            UPDATE ' . $this->tablename . '
            SET ' . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER] .
            ' WHERE ' . self::ID . ' = ' . $id[ISqlHandler::PLACEHOLDER]
            . ' RETURNING ' . self::ID . ' , ' . self::IS_HIDDEN . ' ; ';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $id;
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHidden;

        $record = SqlHandler::readOneRecord($arguments);

        $this->id = Common::setIfExists(self::ID, $record, ISqlHandler::EMPTY_VALUE);
        $this->isHidden = Common::setIfExists(self::IS_HIDDEN, $record, ISqlHandler::EMPTY_VALUE);

        $result = $this->id != self::EMPTY_VALUE && $this->isHidden != ISqlHandler::EMPTY_VALUE;

        return $result;
    }

    /** Формирует массив из свойств экземпляра
     * @return array массив свойств экземпляра
     */
    protected function toEntity():array
    {
        $result [self::ID] = $this->id;
        $result [self::IS_HIDDEN] = $this->isHidden;

        return $result;
    }

    /** Прочитать данные экземпляра из БД
     * @return bool успех выполнения
     */
    protected function getStored():bool
    {
        $result = $this->loadById($this->id);
        return $result;
    }

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
            . ' ,' . self::IS_HIDDEN
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
        $id = Common::setIfExists(self::ID, $namedValue, ISqlHandler::EMPTY_VALUE);
        if (is_null($id)) {
            $id = self::EMPTY_VALUE;
        }
        if ($id != self::EMPTY_VALUE) {
            $this->id = $id;
        }
        $isHidden = strval(Common::setIfExists(self::IS_HIDDEN, $namedValue, ISqlHandler::EMPTY_VALUE));
        if (is_null($isHidden)) {
            $isHidden = self::EMPTY_VALUE;
        }
        if ($isHidden != self::EMPTY_VALUE) {
            $this->isHidden = $isHidden;
        }

        return true;
    }

}
