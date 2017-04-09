<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:01
 */
namespace Remittance\DataAccess\Entity {

    use Remittance\Core\Common;
    use Remittance\Core\ICommon;
    use Remittance\DataAccess\Logic\ISqlHandler;
    use Remittance\DataAccess\Logic\SqlHandler;

    /**
     * Реализация интерфейса для стыковки одной таблицы с другой таблицей
     */
    class InnerLinkageEntity extends Record implements IInnerLinkageEntity
    {
        /** @var string строка соединитель элементов */
        const UNION_BY_AND = " AND ";

        /** @var string константа значение не задано для значимых типов */
        const EMPTY_VALUE = ICommon::EMPTY_VALUE;
        /** @var null константа значение не задано для ссылочных типов */
        const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
        /** @var array константа значение не задано для массивов */
        const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'inner_linkage_entity';
        /** @var string имя одной таблицы */
        const LEFT = 'left_id';
        /** @var string имя другой таблицы */
        const RIGHT = 'right_id';
        public $leftId = self::EMPTY_VALUE;
        public $rightId = self::EMPTY_VALUE;
        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string имя левой таблицы */
        protected $leftColumn = self::LEFT;
        /** @var string имя правой таблицы */
        protected $rightColumn = self::RIGHT;

        /** Удалить стыковку по внешнему ключу правой таблицы
         * @param string $leftId внешний ключ левой таблицы
         * @param string $rightId внешний ключ правой таблицы
         * @return bool успех выполнения
         */
        public function dropLinkageByBoth(string $leftId, string $rightId):bool
        {
            $leftKeyParameter = SqlHandler::setBindParameter(':LEFT_KEY', $leftId, \PDO::PARAM_INT);
            $rightKeyParameter = SqlHandler::setBindParameter(':RIGHT_KEY', $rightId, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] = "
 DELETE FROM $this->tablename 
 WHERE  $this->leftColumn  = " . $leftKeyParameter[ISqlHandler::PLACEHOLDER] . "
 AND $this->rightColumn  = " . $rightKeyParameter[ISqlHandler::PLACEHOLDER] . '
 RETURNING NULL ; ';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $leftKeyParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $rightKeyParameter;

            $records = SqlHandler::writeAllRecords($arguments);

            $deleteCount = 0;
            $isArray = is_array($records);
            if ($isArray) {
                $deleteCount = count($records);
            }

            $result = $deleteCount > 0;
            return $result;
        }

        /** Удалить стыковку по внешнему ключу правой таблицы
         * @param string $id внешней ключ правой таблицы
         * @return bool успех выполнения
         */
        public function dropLinkageByRight(string $id):bool
        {

            $result = $this->dropLinkageByColumn($id, $this->rightColumn);

            return $result;
        }

        /** Удалить стыковку по внешнему ключу левой таблицы
         * @param string $id внешней ключ левой таблицы
         * @param string $columnName
         * @return bool успех выполнения
         */
        protected function dropLinkageByColumn(string $id, string $columnName):bool
        {
            $foreignKeyParameter = SqlHandler::setBindParameter(':FOREIGN_KEY_VALUE', $id, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                " DELETE FROM $this->tablename "
                . ' WHERE ' . $columnName . ' = ' . $foreignKeyParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING NULL ; ';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $foreignKeyParameter;

            $records = SqlHandler::writeAllRecords($arguments);

            $deleteCount = 0;
            $isArray = is_array($records);
            if ($isArray) {
                $deleteCount = count($records);
            }

            $result = $deleteCount > 0;
            return $result;
        }

        /** Удалить стыковку по внешнему ключу левой таблицы
         * @param string $id внешней ключ левой таблицы
         * @return bool успех выполнения
         */
        public function dropLinkageByLeft(string $id):bool
        {

            $result = $this->dropLinkageByColumn($id, $this->leftColumn);
            return $result;
        }

        /** Добавить запись в БД с заданной внунней ссылкой
         * @param string $leftId внешний ключ одной таблицы
         * @param string $rightId внешний ключ другой таблицы
         * @return bool успех выполнения
         */
        public function addInnerLinkage(string $leftId, string $rightId):bool
        {

            $leftParameter = SqlHandler::setBindParameter(':FOREIGN_KEY_LEFT', $leftId, \PDO::PARAM_INT);
            $rightParameter = SqlHandler::setBindParameter(':FOREIGN_KEY_RIGHT', $rightId, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' INSERT INTO ' . " $this->tablename ( $this->leftColumn , $this->rightColumn ) "
                . 'VALUES ('
                . $leftParameter[ISqlHandler::PLACEHOLDER]
                . ',' . $rightParameter[ISqlHandler::PLACEHOLDER]
                . ') RETURNING ' . self::ID
                . ' , ' . $this->leftColumn
                . ' , ' . $this->rightColumn
                . ' ; ';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $leftParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $rightParameter;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = $record != ISqlHandler::EMPTY_ARRAY;

            return $result;
        }

        /** Загрузить значения по ссылке на правую таблицу
         * @param string $rightId внешний ключ правой таблицы
         * @return bool успех выполнения
         */
        public function loadByRight(string $rightId):bool
        {

            $result = $this->loadByForeignKey($rightId, $this->rightColumn);

            return $result;
        }

        /**
         * @param string $keyId
         * @param $keyColumn
         * @return bool
         */
        private function loadByForeignKey(string $keyId, $keyColumn)
        {
            $idParameter = SqlHandler::setBindParameter(':FOREIGN_KEY', $keyId, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . self::ID
                . ' , ' . $this->leftColumn
                . ' , ' . $this->rightColumn
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . $keyColumn . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = $record != ISqlHandler::EMPTY_ARRAY;
            if ($result) {
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
            if ($id != self::EMPTY_VALUE) {
                $this->id = $id;
            }
            $leftId = Common::setIfExists($this->leftColumn, $namedValue, ISqlHandler::EMPTY_VALUE);
            if ($leftId != self::EMPTY_VALUE) {
                $this->leftId = $leftId;
            }
            $rightId = Common::setIfExists($this->rightColumn, $namedValue, ISqlHandler::EMPTY_VALUE);
            if ($rightId != self::EMPTY_VALUE) {
                $this->rightId = $rightId;
            }

            return true;
        }

        /** Загрузить значения по ссылке на левую таблицу
         * @param string $leftId внешний ключ левой таблицы
         * @return bool успех выполнения
         */
        public function loadByLeft(string $leftId):bool
        {
            $result = $this->loadByForeignKey($leftId, $this->leftColumn);

            return $result;
        }

    }
}
