<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.01.2017
 * Time: 13:37
 */

namespace Remittance\DataAccess\Logic;


use Remittance\Core\ICommon;

interface ISqlHandler
{
    /** @var string константа значение не задано для значимых типов */
    const EMPTY_VALUE = ICommon::EMPTY_VALUE;
    /** @var null константа значение не задано для ссылочных типов */
    const EMPTY_OBJECT = ICommon::EMPTY_OBJECT;
    /** @var array константа значение не задано для массивов */
    const EMPTY_ARRAY = ICommon::EMPTY_ARRAY;

    /** @var string индекс для текста запроса */
    const QUERY_TEXT = 'QUERY_TEXT';
    /** @var string индекс для параметра запроса */
    const QUERY_PARAMETER = 'QUERY_PARAMETER';
    /** @var string индекс для место заменителя параметра */
    const PLACEHOLDER = 'PARAMETER_PLACEHOLDER';
    /** @var string индекс для значения параметра */
    const VALUE = 'PARAMETER_VALUE';
    /** @var string индекс для типа данных параметра */
    const DATA_TYPE = 'PARAMETER_DATA_TYPE';

    /** @var string индекс для массива с данными выборки в ответе СУБД */
    const RECORDS = 'fetchAll';
    /** @var string индекс для массива с ошибкой выборки в ответе СУБД */
    const ERROR_INFO = 'errorInfo';

    /** @var string код ошибки PDO PostrgesSql для успешно выполнения запроса */
    const EXEC_WITH_SUCCESS_CODE = '00000';
    /** @var null сообщение PDO PostrgesSql для успешно выполнения запроса */
    const EXEC_WITH_SUCCESS_MESSAGE = null;
    /** @var null номер ошибки PDO PostrgesSql для успешно выполнения запроса */
    const EXEC_WITH_SUCCESS_NUMBER = null;

    /** @var int индекс кода ошибки в отчёте о результате выполнения запроса */
    const EXEC_ERROR_CODE_INDEX = 0;
    /** @var int индекс сообщения ошибки в отчёте о результате выполнения запроса */
    const EXEC_ERROR_NUMBER_INDEX = 1;
    /** @var int индекс номера ошибки в отчёте о результате выполнения запроса */
    const EXEC_ERROR_MESSAGE_INDEX = 2;

    /** @var int режим чтения данных */
    const DATA_READER = 1;
    /** @var int режим записи данных */
    const DATA_WRITER = 2;

    /** Задать параметры связывания для параметризированного запроса
     * @param string $placeholder место заменитель параметра
     * @param string $value значение параметра
     * @param int $dataType тип значения параметра
     * @return array
     */
    public static function setBindParameter(string $placeholder, string $value, int $dataType):array;

    /** Прочитать все результаты запроса данных
     * @param $arguments array аргументы выборки данных
     * @return array данные выборки
     */
    public static function readAllRecords(array $arguments):array;

    /** Прочитать одну строку из выборки данных
     * @param $arguments array аргументы выборки данных
     * @return array результат чтения данных
     */
    public static function readOneRecord(array $arguments):array;

    /** Сделать одну запись
     * @param $arguments array параметры записи
     * @return array записанные значения
     */
    public static function writeOneRecord($arguments):array;

    /** Записать все строки
     * @param $arguments array аргументы записи
     * @return array результат записи
     */
    public static function writeAllRecords(array $arguments):array;

    /** Сформировать условие разбивки на страницы
     * @param int $start с какой позиции показывать
     * @param int $paging сколько позиций показать
     * @return string условие разбивки на страницы
     */
    public static function getPagingCondition(int $start, int $paging):string;

    /**
     * @param $key
     * @param $namedValue
     * @return mixed|string
     */
    public static function setIfExists($key, &$namedValue);
}
