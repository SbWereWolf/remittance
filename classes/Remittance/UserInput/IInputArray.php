<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 11.04.2017
 * Time: 18:27
 */

namespace Remittance\UserInput;


use Remittance\Core\ICommon;
use Slim\Http\Request;

interface IInputArray
{
    /** @var string константа значение не задано для значимых типов */
    const EMPTY_VALUE = ICommon::EMPTY_VALUE;

    public function getSpecialCharsValue(string $key, array $options = array()):string;

    public function getBooleanValue(string $key, array $options = array()):bool;

    public function getIntegerValue(string $key, array $options = array()):int;

    public function getFloatValue(string $key, array $options = array()):float;

    /** Получить данные html-формы, сформированные с помощью jQuery.serialize
     * @param Request $request запрос к серверу
     * @return InputArray массив ключ - значение
     */
    public static function getFormData(Request $request): InputArray;
}
