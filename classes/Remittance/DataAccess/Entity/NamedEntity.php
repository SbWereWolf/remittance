<?php

namespace Remittance\DataAccess\Entity {

    use Remittance\Core\ICommon;
    use Remittance\DataAccess\Logic\ISqlHandler;
    use Remittance\DataAccess\Logic\SqlHandler;

    /**
     * Реализация интерфейса для работы с именнуемыми сущностями
     */
    class NamedEntity extends Entity implements INamedEntity
    {
        /** @var string константа значение не задано для значимых типов */
        const EMPTY_VALUE = ICommon::EMPTY_VALUE;
        /** @var null константа значение не задано для ссылочных типов */
        const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
        /** @var array константа значение не задано для массивов */
        const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

        /** @var string имя таблицы БД для хранения сущности */
        const TABLE_NAME = 'named_entity';
        /** @var string код */
        public $code = self::EMPTY_VALUE;
        /** @var string имя */
        public $title = self::EMPTY_VALUE;
        /** @var string описание */
        public $description = self::EMPTY_VALUE;
        /** @var string имя таблицы БД для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        protected $classname = self::class;

        public function save():bool
        {
            $code = SqlHandler::setBindParameter(':CODE', $this->code, \PDO::PARAM_STR);
            $title = SqlHandler::setBindParameter(':TITLE', $this->title, \PDO::PARAM_STR);
            $description = SqlHandler::setBindParameter(':DESCRIPTION', $this->description, \PDO::PARAM_STR);

            $isHidden = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                ' UPDATE '
                . $this->tablename
                . ' SET '
                . self::CODE . ' = ' . $code[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::TITLE . ' = ' . $title[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $description[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHidden[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::CODE . ' = ' . $code[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , ' . self::CODE
                . ' , ' . self::TITLE
                . ' , ' . self::DESCRIPTION
                . ';';

            $arguments[ISqlHandler::QUERY_PARAMETER][] = $code;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $title;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $description;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHidden;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record !== ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }

            return $result;
        }

        public static function adopt($object): NamedEntity
        {

            return $object;

        }

        public function setByNamedValue(array $namedValues): bool
        {

            $result = parent::setByNamedValue($namedValues);

            $code = SqlHandler::setIfExists(self::CODE, $namedValues);
            if ($code !== ISqlHandler::EMPTY_VALUE) {
                $this->code = $code;
            }
            $title = SqlHandler::setIfExists(self::TITLE, $namedValues);
            if ($title !== ISqlHandler::EMPTY_VALUE) {
                $this->title = $title;
            }
            $description = SqlHandler::setIfExists(self::DESCRIPTION, $namedValues);
            if ($description !== ISqlHandler::EMPTY_VALUE) {
                $this->description = $description;
            }

            return $result;
        }

        /** Получить имя и описание записи
         * @param string $code значение ключа для свойства код
         * @param string $title значение ключа для свойства имя
         * @param string $description значение ключа для свойства описание
         * @return array массив с именем и описанием
         */
        public function getElementDescription(string $code = self::CODE,
                                              string $title = self::TITLE,
                                              string $description = self::DESCRIPTION): array
        {
            $result[$code] = $this->code;
            $result[$title] = $this->title;
            $result[$description] = $this->description;
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
                . ' , ' . self::CODE
                . ' , ' . self::TITLE
                . ' , ' . self::DESCRIPTION
                . ' , ' . self::IS_HIDDEN
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

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity(): array
        {
            $result = parent::toEntity();

            $result [self::CODE] = $this->code;
            $result [self::TITLE] = $this->title;
            $result [self::DESCRIPTION] = $this->description;

            return $result;
        }

        /** Обновить данные в БД
         * @return bool успех выполнения
         */
        protected function updateEntity(): bool
        {

            $codeParameter = SqlHandler::setBindParameter(':CODE', $this->code, \PDO::PARAM_STR);
            $descriptionParameter = SqlHandler::setBindParameter(':DESCRIPTION', $this->description, \PDO::PARAM_STR);
            $idParameter = SqlHandler::setBindParameter(':ID', $this->id, \PDO::PARAM_INT);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', $this->isHidden, \PDO::PARAM_INT);
            $nameParameter = SqlHandler::setBindParameter(':NAME', $this->title, \PDO::PARAM_STR);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'UPDATE '
                . $this->tablename
                . ' SET '
                . self::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::TITLE . ' = ' . $nameParameter[ISqlHandler::PLACEHOLDER]
                . ' , ' . self::DESCRIPTION . ' = ' . $descriptionParameter[ISqlHandler::PLACEHOLDER]
                . ' WHERE '
                . self::ID . ' = ' . $idParameter[ISqlHandler::PLACEHOLDER]
                . ' RETURNING '
                . self::ID
                . ' , ' . self::IS_HIDDEN
                . ' , btrim(' . self::CODE . ') AS "' . self::CODE . '"'
                . ' , ' . self::TITLE
                . ' , ' . self::DESCRIPTION
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $descriptionParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $idParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $nameParameter;

            $record = SqlHandler::writeOneRecord($arguments);

            $result = false;
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result = $this->setByNamedValue($record);
            }
            return $result;
        }
    }
}
