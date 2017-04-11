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

    /** @var string имя таблицы БД для хранения сущности */
    protected $tablename = self::TABLE_NAME;
    protected $classname = self::class;

    /** Обновляет (изменяет) запись в БД
     * @return bool успех выполнения
     */
    public function mutateEntity():bool
    {
        $result = false;

        $stored = new $this->classname();
        $wasReadStored = $stored->loadById($this->id);

        $storedEntity = array();
        $entity = array();
        if ($wasReadStored) {
            $storedEntity = $stored->toEntity();
            $entity = $this->toEntity();
        }

        $isContain = Common::isOneArrayContainOther($entity, $storedEntity);

        if (!$isContain) {
            $result = $this->updateEntity();
        }

        return $result;
    }

    /** Обновить данные в БД
     * @return bool успех выполнения
     */
    protected function updateEntity():bool
    {
        return false;
    }

    /** Формирует массив из свойств экземпляра
     * @return array массив свойств экземпляра
     */
    public function toEntity():array
    {
        $result [self::ID] = $this->id;

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

        $id = SqlHandler::setIfExists(self::ID, $namedValue);
        if ($id !== SqlHandler::EMPTY_VALUE) {
            $this->id = $id;
        }

        return true;
    }

}
