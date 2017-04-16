<?php

namespace Remittance\Core {
    /**
     * Реализация интерфейса для методов общего назначения
     */
    class Common implements ICommon
    {
        public static function setIfExists($key, &$array, $valueIfNotIsset)
        {

            $isArray = is_array($array);

            $maySet = false;
            if ($isArray) {
                $maySet = array_key_exists($key, $array);
            }

            $value = self::EMPTY_VALUE;
            if ($maySet) {
                $value = self::isSetEx($array[$key], self::EMPTY_VALUE);
            }

            return $value;
        }

        public static function isSetEx($valueIfIsset, $valueIfNotIsset)
        {
            $value = isset($valueIfIsset) ? $valueIfIsset : $valueIfNotIsset;
            return $value;
        }

        /** Проверяет что один массив полностью содержит элементы второго
         * @param $oneArray array первый массив
         * @param $otherArray array второй массив
         * @return bool
         */
        public static function isOneArrayContainOther(array $oneArray, array $otherArray): bool
        {
            $isContain = true;
            foreach ($otherArray as $key => $column) {
                $isExist = array_key_exists($key, $oneArray) && array_key_exists($key, $otherArray);
                $equal = false;
                if ($isExist) {
                    $equal = $column == $oneArray[$key];
                }
                if (!$equal) {
                    $isContain = false;
                }
            }
            return $isContain;
        }

        /** Проверить что массив не пустой
         * @param $arrayCandidate mixed массив
         * @return bool полученная переменная является массивом в котором содержатся один и больше элементов
         */
        public static function isValidArray($arrayCandidate): bool
        {
            $isSet = isset($arrayCandidate);
            $isArray = false;
            $isContain = false;
            if ($isSet) {
                $isArray = is_array($arrayCandidate);
                $isContain = count($arrayCandidate) > 0;
            }
            $isValid = $isArray && $isContain;

            return $isValid;
        }
    }
}
