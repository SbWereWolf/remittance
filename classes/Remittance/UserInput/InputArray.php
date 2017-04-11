<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 11.04.2017
 * Time: 18:27
 */

namespace Remittance\UserInput;


class InputArray implements IInputArray
{

    private $userArray = array();

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

    public function getSpecialCharsValue(string $key, array $options = array()):string
    {

        $value = $this->getValue($key, FILTER_SANITIZE_SPECIAL_CHARS, $options);

        $result = (string)$value;

        return $result;
    }

    public function getBooleanValue(string $key, array $options = array()):bool
    {

        $value = $this->getValue($key, FILTER_VALIDATE_BOOLEAN, $options);

        $result = (bool)$value;

        return $result;
    }

    public function getIntegerValue(string $key, array $options = array()):int
    {

        $value = $this->getValue($key, FILTER_VALIDATE_INT, $options);

        $result = (int)$value;

        return $result;
    }
    public function getFloatValue(string $key, array $options = array()):float
    {

        $value = $this->getValue($key, FILTER_VALIDATE_FLOAT, $options);

        $result = (float)$value;

        return $result;
    }
}
