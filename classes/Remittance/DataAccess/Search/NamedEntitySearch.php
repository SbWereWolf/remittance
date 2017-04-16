<?php

namespace Remittance\DataAccess\Search {

    use Remittance\Core\Common;
    use Remittance\Core\ICommon;
    use Remittance\DataAccess\Entity\CurrencyRecord;
    use Remittance\DataAccess\Entity\NamedEntity;
    use Remittance\DataAccess\Logic\ISqlHandler;
    use Remittance\DataAccess\Logic\SqlHandler;

    /**
     * Реализация интерфейса для работы с именнуемыми сущностями
     */
    class NamedEntitySearch
    {
        /** @var string константа значение не задано для значимых типов */
        const EMPTY_VALUE = ICommon::EMPTY_VALUE;
        /** @var null константа значение не задано для ссылочных типов */
        const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
        /** @var array константа значение не задано для массивов */
        const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

        protected $tablename = 'search_in_tablename';

        public function __construct(string $tablename)
        {
            $this->tablename = $tablename;
        }

        /** Загрузить по коду записи
         * @param string $code код записи
         * @return NamedEntity результат поиска, new NamedEntity() если поиск не дал результата
         */
        public function searchByCode(string $code): NamedEntity
        {

            $codeParameter = SqlHandler::setBindParameter(':CODE', $code, \PDO::PARAM_STR);
            $isHiddenParameter = SqlHandler::setBindParameter(':IS_HIDDEN', NamedEntity::DEFINE_AS_NOT_HIDDEN, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . NamedEntity::ID
                . ' , ' . NamedEntity::CODE
                . ' , ' . NamedEntity::TITLE
                . ' , ' . NamedEntity::DESCRIPTION
                . ' , ' . NamedEntity::IS_HIDDEN
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . NamedEntity::CODE . ' = ' . $codeParameter[ISqlHandler::PLACEHOLDER]
                . ' AND ' . NamedEntity::IS_HIDDEN . ' = ' . $isHiddenParameter[ISqlHandler::PLACEHOLDER]
                . '
;
';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $codeParameter;
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $isHiddenParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = new NamedEntity();
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result->setByNamedValue($record);
            }

            return $result;
        }

        /** Прочитать запись из БД
         * @param string $id идентификатор записи
         * @return NamedEntity результат поиска, new NamedEntity() если поиск не дал результата
         */
        public function searchById(string $id): NamedEntity
        {

            $oneParameter = SqlHandler::setBindParameter(':ID', $id, \PDO::PARAM_INT);

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . NamedEntity::ID
                . ' , ' . NamedEntity::CODE
                . ' , ' . NamedEntity::TITLE
                . ' , ' . NamedEntity::DESCRIPTION
                . ' , ' . NamedEntity::IS_HIDDEN
                . ' FROM '
                . $this->tablename
                . ' WHERE '
                . NamedEntity::ID . ' = ' . $oneParameter[ISqlHandler::PLACEHOLDER]
                . ';';
            $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

            $record = SqlHandler::readOneRecord($arguments);

            $result = new NamedEntity();
            if ($record != ISqlHandler::EMPTY_ARRAY) {
                $result->setByNamedValue($record);
            }

            return $result;
        }

        public function search(array $filterProperties = array(), int $start = 0, int $paging = 0): array
        {

            $arguments[ISqlHandler::QUERY_TEXT] =
                'SELECT '
                . NamedEntity::ID
                . ' , ' . NamedEntity::CODE
                . ' , ' . NamedEntity::TITLE
                . ' , ' . NamedEntity::DESCRIPTION
                . ' , ' . NamedEntity::IS_HIDDEN
                . ' FROM '
                . $this->tablename
                . ' ORDER BY ' . NamedEntity::ID . ' DESC'
                . ';';

            $records = SqlHandler::readAllRecords($arguments);

            $isContain = count($records);
            $result = ICommon::EMPTY_ARRAY;
            if ($isContain) {
                foreach ($records as $recordValues) {
                    $namedEntity = new NamedEntity();
                    $namedEntity->setByNamedValue($recordValues);
                    $result[] = $namedEntity;
                }
            }

            return $result;
        }

        public function searchCurrency(array $filterProperties = array(), int $start = 0, int $paging = 0): array
        {
            $records = $this->search($filterProperties, $start, $paging);
            $isValid = Common::isValidArray($records);

            $currencies = ICommon::EMPTY_ARRAY;
            if ($isValid) {

                foreach ($records as $candidate) {
                    $isNamedEntity = $candidate instanceof NamedEntity;
                    $asArray = ICommon::EMPTY_ARRAY;
                    if ($isNamedEntity) {
                        $namedEntity = NamedEntity::adopt($candidate);
                        $asArray = $namedEntity->toEntity();
                    }

                    $isValid = Common::isValidArray($asArray);
                    if ($isValid) {

                        $currency = new CurrencyRecord();
                        $currency->setByNamedValue($asArray);
                        $currencies[] = $currency;
                    }
                }
            }

            return $currencies;
        }

    }
}
