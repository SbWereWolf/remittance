<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.01.2017
 * Time: 22:30
 */

namespace Remittance\DataAccess\Entity;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Logic\ISqlHandler;
use Remittance\DataAccess\Logic\SqlHandler;

class OuterLinkageEntity extends Record implements IOuterLinkageEntity
{
    /** @var null константа значение не задано для ссылочных типов */
    const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
    /** @var array константа значение не задано для массивов */
    const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

    /** @var string имя таблицы БД для хранения записи */
    const TABLE_NAME = 'outer_linkage_entity';

    /** @var string имя таблицы БД для хранения записи */
    protected $tablename = self::TABLE_NAME;

    /** Удалить объект для внешней ссылки
     * @return bool успех выполнения
     */
    public function dropOuterLinkage():bool
    {
        $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);

        $arguments[ISqlHandler::QUERY_TEXT] =
            " DELETE FROM $this->tablename WHERE "
            . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
            . ' RETURNING NULL ; ';

        $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

        $records = SqlHandler::writeAllRecords($arguments);

        $deleteCount = 0;
        $isArray = is_array($records);
        if ($isArray) {
            $deleteCount = count($records);
        }

        $result = $deleteCount > 0;
        return $result;
    }

    /** Добавить объект для внешней ссылки
     * @return string идентификатор объекта для внешней ссылки
     */
    public function addOuterLinkage():string
    {
        $arguments[ISqlHandler::QUERY_TEXT] =
            ' INSERT INTO ' . $this->tablename
            . ' DEFAULT VALUES RETURNING '
            . self::ID
            . ' ; ';

        $record = SqlHandler::readOneRecord($arguments);

        if ($record != self::EMPTY_ARRAY) {
            $this->id = Common::setIfExists(self::ID, $record, self::EMPTY_VALUE);
        }
        $result = $this->id;

        return $result;
    }
}
