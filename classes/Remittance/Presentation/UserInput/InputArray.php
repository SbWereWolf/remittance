<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 11.04.2017
 * Time: 18:27
 */

namespace Remittance\Presentation\UserInput;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Slim\Http\Request;

class InputArray implements IInputArray
{
    const FORM_DATA = 'form_data';

    private $userArray = ICommon::EMPTY_ARRAY;

    public function __construct(array $userArray)
    {
        $this->userArray = $userArray;
    }

    private function getValue(string $key, int $filter, array $filterOptions = array())
    {
        $isKeyExists = array_key_exists($key, $this->userArray);
        $clearValue = self::EMPTY_VALUE;
        if ($isKeyExists) {
            $value = $this->userArray[$key];
            $clearValue = filter_var($value, $filter, $filterOptions);
        }
        return $clearValue;
    }

    public function getSpecialCharsValue(string $key, array $options = array()): string
    {

        $value = $this->getValue($key, FILTER_SANITIZE_SPECIAL_CHARS, $options);

        $result = strval($value);

        return $result;
    }

    public function getBooleanValue(string $key, array $options = array()): bool
    {

        $value = $this->getValue($key, FILTER_VALIDATE_BOOLEAN, $options);

        $result = boolval($value);

        return $result;
    }

    public function getIntegerValue(string $key, array $options = array()): int
    {

        $value = $this->getValue($key, FILTER_VALIDATE_INT, $options);

        $result = intval($value);

        return $result;
    }

    public function getFloatValue(string $key, array $options = array()): float
    {

        $value = $this->getValue($key, FILTER_VALIDATE_FLOAT, $options);

        $result = floatval($value);

        return $result;
    }

    /** Получить данные html-формы, сформированные с помощью jQuery.serialize
     * @param Request $request запрос к серверу
     * @return InputArray массив ключ - значение
     */
    public static function getFormData(Request $request): InputArray
    {
        $parsedBody = $request->getParsedBody();
        $formData = Common::setIfExists(self::FORM_DATA, $parsedBody, ICommon::EMPTY_VALUE);

        $isValid = !empty($formData);
        $volumeData = ICommon::EMPTY_ARRAY;
        if ($isValid) {
            parse_str($formData, $volumeData);
        }

        $inputArray = new InputArray($volumeData);
        return $inputArray;
    }
}
