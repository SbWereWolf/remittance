<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.01.2017
 * Time: 14:17
 */

namespace Remittance\DataAccess\Logic;

use Remittance\Core\Common;
use Remittance\Core\ICommon;

class SqlHandler implements ISqlHandler
{

    private $dataSource = ICommon::EMPTY_VALUE;
    private $dbLogin = ICommon::EMPTY_VALUE;
    private $dbPassword = ICommon::EMPTY_VALUE;

    public function __construct($type = ISqlHandler::DATA_READER)
    {
        $dbCredentials = array();
        switch ($type) {
            case ISqlHandler::DATA_READER :
                $dbCredentials = DbCredentials::getDbReader();
                break;
            case ISqlHandler::DATA_WRITER :
                $dbCredentials = DbCredentials::getDbWriter();
                break;
        }

        $this->dataSource = Common::setIfExists(
            IDbCredentials::DATA_SOURCE_NAME,
            $dbCredentials,
            ISqlHandler::EMPTY_VALUE);
        $this->dbLogin = Common::setIfExists(
            IDbCredentials::LOGIN,
            $dbCredentials,
            ISqlHandler::EMPTY_VALUE);
        $this->dbPassword = Common::setIfExists(
            IDbCredentials::PASSWORD,
            $dbCredentials,
            ISqlHandler::EMPTY_VALUE);
    }

    /** Установить настройки параметра для запроса
     * @param string $placeholder место заменитель
     * @param string $value значение
     * @param int $dataType тип данных для значения
     * @return array настройки параметра для запроса
     */
    public static function setBindParameter(string $placeholder, string $value, int $dataType):array
    {
        $bindValue = $value;
        switch ($dataType) {
            case \PDO::PARAM_INT :
                $bindValue = intval($value);
                break;
            case \PDO::PARAM_STR:
                $bindValue = strval($value);
                break;
        }
        $result = [
            ISqlHandler::PLACEHOLDER => $placeholder,
            ISqlHandler::VALUE => $bindValue,
            ISqlHandler::DATA_TYPE => $dataType,
        ];

        return $result;
    }

    /** Прочитать все результаты запроса данных
     * @param $arguments array аргументы выборки данных
     * @return array данные выборки
     */
    public static function readAllRecords(array $arguments):array
    {
        $sqlReader = new self(self::DATA_READER);
        $response = $sqlReader->performQuery($arguments);
        $isSuccessfulRead = self::isErrorAbsent($response);

        $result = ISqlHandler::EMPTY_ARRAY;
        if ($isSuccessfulRead) {
            $result = self::getAllRecords($response);
        }
        return $result;
    }

    /** выполнить запрос к СУБД с использованием PDO
     * @param array $arguments параметры запроса
     * @return array ответ сервера
     */
    private function performQuery(array $arguments):array
    {

        $connection = new \PDO ($this->dataSource,
            $this->dbLogin,
            $this->dbPassword);
        $dbQuery = self::getPdoStatement($connection, $arguments);
        $this->bindParameterValue($dbQuery, $arguments);
        $dbQuery->execute();

        $records = $dbQuery->fetchAll(\PDO::FETCH_ASSOC);
        $errorInfo = $dbQuery->errorInfo();

        $result[ISqlHandler::RECORDS] = $records;
        $result[ISqlHandler::ERROR_INFO] = $errorInfo;

        return $result;
    }

    /** Получить PDO выражение
     * @param \PDO $connection
     * @param array $parameters
     * @return \PDOStatement
     */
    private static function getPdoStatement(\PDO $connection, array $parameters):\PDOStatement
    {
        $queryText = Common::setIfExists(ISqlHandler::QUERY_TEXT, $parameters, ICommon::EMPTY_VALUE);

        $statement = ISqlHandler::EMPTY_OBJECT;
        if ($queryText != ISqlHandler::EMPTY_VALUE) {
            $statement = $connection->prepare($queryText);
        }

        return $statement;
    }

    /**
     * @param \PDOStatement $dbQuery
     * @param array $arguments
     * @internal param $emptyValue
     */
    private function bindParameterValue(\PDOStatement $dbQuery, array $arguments)
    {
        $emptyValue = ISqlHandler::EMPTY_VALUE;

        $queryParameters = Common::setIfExists(ISqlHandler::QUERY_PARAMETER, $arguments, $emptyValue);

        $isArgumentsEmpty = $queryParameters == $emptyValue;
        if (!$isArgumentsEmpty) {
            foreach ($queryParameters as $queryParameter) {

                $placeholder = Common::setIfExists(ISqlHandler::PLACEHOLDER, $queryParameter, $emptyValue);
                $value = Common::setIfExists(ISqlHandler::VALUE, $queryParameter, $emptyValue);
                $dataType = Common::setIfExists(ISqlHandler::DATA_TYPE, $queryParameter, $emptyValue);

                $isParametersEmpty = ($placeholder == $emptyValue) || ($dataType == $emptyValue);
                if (!$isParametersEmpty) {
                    $dbQuery->bindValue($placeholder, $value, $dataType);
                }
            }
        }
    }

    /** Проверить на отсутствие ошибки в ответе сервера
     * @param array $response ответ сервера на зпрос
     * @return bool флаг отсутствия ошибки
     */
    private static function isErrorAbsent(array $response):bool
    {
        $errorInfo = Common::setIfExists(ISqlHandler::ERROR_INFO,
            $response,
            ISqlHandler::EMPTY_VALUE);

        $errorCode = ISqlHandler::EMPTY_VALUE;
        $errorNumber = ISqlHandler::EMPTY_VALUE;
        $errorMessage = ISqlHandler::EMPTY_VALUE;
        if ($errorInfo != ISqlHandler::EMPTY_VALUE) {
            $errorCode = $errorInfo[ISqlHandler::EXEC_ERROR_CODE_INDEX];
            $errorNumber = $errorInfo[ISqlHandler::EXEC_ERROR_NUMBER_INDEX];
            $errorMessage = $errorInfo[ISqlHandler::EXEC_ERROR_MESSAGE_INDEX];
        }
        $isSuccessfulRequest = false;
        if ($errorCode != ISqlHandler::EMPTY_VALUE) {
            $isSuccessfulRequest = $errorCode == ISqlHandler::EXEC_WITH_SUCCESS_CODE
                && $errorNumber == ISqlHandler::EXEC_WITH_SUCCESS_NUMBER
                && $errorMessage == ISqlHandler::EXEC_WITH_SUCCESS_MESSAGE;
        }
        return $isSuccessfulRequest;
    }

    /** Получить все строки выборки
     * @param array $response ответ сервера на запрос
     * @return array данные выборки
     */
    private static function getAllRecords(array $response):array
    {
        $records = Common::setIfExists(ISqlHandler::RECORDS,
            $response,
            array());

        return $records;
    }

    /** Прочитать одну строку из выборки данных
     * @param $arguments array аргументы выборки данных
     * @return array результат чтения данных
     */
    public static function readOneRecord(array $arguments):array
    {
        $sqlReader = new self(ISqlHandler::DATA_READER);
        $response = $sqlReader->performQuery($arguments);

        $isSuccessfulRead = self::isErrorAbsent($response);

        $record = ISqlHandler::EMPTY_ARRAY;
        if ($isSuccessfulRead) {
            $record = self::getFirstRecord($response);
        }

        return $record;
    }

    /** Получить первую строку выборки
     * @param array $response ответ сервера на запрос
     * @return array данные первой строки
     */
    private static function getFirstRecord(array $response):array
    {
        $records = Common::setIfExists(ISqlHandler::RECORDS,
            $response,
            ISqlHandler::EMPTY_VALUE);

        $responseValue = ISqlHandler::EMPTY_ARRAY;
        if ($records != ISqlHandler::EMPTY_VALUE) {
            $responseIndex = 0;
            $responseValue = Common::setIfExists($responseIndex,
                $records,
                array());
        }

        $isArray = is_array($responseValue);
        if (!$isArray) {
            $responseValue = ISqlHandler::EMPTY_ARRAY;
        }

        return $responseValue;
    }

    /** Сделать одну запись
     * @param $arguments array параметры записи
     * @return array записанные значения
     */
    public static function writeOneRecord($arguments):array
    {
        $sqlWriter = new self(ISqlHandler::DATA_WRITER);
        $response = $sqlWriter->performQuery($arguments);

        $isSuccessfulRequest = self::isErrorAbsent($response);

        $record = ISqlHandler::EMPTY_ARRAY;
        if ($isSuccessfulRequest) {
            $record = self::getFirstRecord($response);

        }

        return $record;
    }

    /** Записать все строки
     * @param $arguments array аргументы записи
     * @return array результат записи
     */
    public static function writeAllRecords(array $arguments):array
    {
        $sqlWriter = new self(ISqlHandler::DATA_WRITER);
        $response = $sqlWriter->performQuery($arguments);

        $isSuccessfulDelete = self::isErrorAbsent($response);

        $records = ISqlHandler::EMPTY_ARRAY;
        if ($isSuccessfulDelete) {
            $records = self::getAllRecords($response);
        }

        return $records;
    }

    /** Сформировать условие разбивки на страницы
     * @param int $start с какой позиции показывать
     * @param int $paging сколько позиций показать
     * @return string условие разбивки на страницы
     */
    public static function getPagingCondition(int $start, int $paging):string
    {
        $pagingString = '';

        $queryLimit = '';
        if ($paging > 0) {
            $queryLimit = " LIMIT $paging ";
        }
        $pagingString .= $queryLimit;

        $queryOffset = '';
        if ($start > 0) {
            $queryOffset = "  OFFSET $start ";
        }
        $pagingString .= $queryOffset;
        return $pagingString;
    }

    /**
     * @param $key
     * @param $namedValue
     * @return mixed|string
     */
    public static function setIfExists($key, &$namedValue)
    {
        $some = ISqlHandler::EMPTY_VALUE;
        $value = Common::setIfExists($key, $namedValue, ISqlHandler::EMPTY_VALUE);
        $isNull = is_null($value);
        if ($isNull) {
            $value = ISqlHandler::EMPTY_VALUE;
        }
        $isEmpty = $value == ISqlHandler::EMPTY_VALUE;
        if (!$isEmpty) {
            $some = $value;
        }

        return $some;
    }
}
