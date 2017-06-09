<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:00
 */

namespace Remittance\DataAccess\Entity {

    use Remittance\Core\ICommon;
    use Remittance\DataAccess\Column\IHidden;
    use Remittance\DataAccess\Logic\ISqlHandler;
    use Remittance\DataAccess\Logic\SqlHandler;

    /**
     * реализация интерфейса для работы с именнуемыми сущностями
     */
    class Entity extends PrimitiveData implements IEntity, IHidden
    {
        /** @var null константа значение не задано для ссылочных типов */
        const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
        /** @var array константа значение не задано для массивов */
        const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'entity';

        /** @var string флаг "является скрытым" */
        public $isHidden = self::DEFINE_AS_NOT_HIDDEN;
        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;

        /** Скрыть сущность
         * @return bool успех выполнения
         */
        public function hideEntity(): bool
        {
            $this->isHidden = self::DEFINE_AS_HIDDEN;
            $result = $this->mutateEntity();

            return $result;
        }

        /** Установить свойства экземпляра в соответствии со значениями
         * @param array $namedValue массив значений
         * @return bool успех выполнения
         */
        public function setByNamedValue(array $namedValue): bool
        {
            $result = parent::setByNamedValue($namedValue);

            $isHidden = SqlHandler::setIfExists(self::IS_HIDDEN, $namedValue);
            if ($isHidden !== SqlHandler::EMPTY_VALUE) {
                $this->isHidden = boolval($isHidden);
            }

            return $result;
        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity(): array
        {
            $result = parent::toEntity();

            $result [self::IS_HIDDEN] = intval($this->isHidden);

            return $result;
        }

        public function addEntity(): bool
        {
            $arguments[ISqlHandler::QUERY_TEXT] =
                ' INSERT INTO ' . $this->tablename
                . ' DEFAULT VALUES RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' ; ';

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            $result = $result && $this->id !== ISqlHandler::EMPTY_VALUE && $this->isHidden !== ISqlHandler::EMPTY_VALUE;

            return $result;
        }

        /** Обновить данные в БД
         * @return bool успех выполнения
         */
        protected function updateEntity(): bool
        {

            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . $this->tablename
                . ' SET '
                . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record !== ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }
            return $result;
        }

    }
}
