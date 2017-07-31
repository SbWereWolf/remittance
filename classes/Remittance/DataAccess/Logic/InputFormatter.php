<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-07-28
 * Time: 18:40
 */

namespace Remittance\DataAccess\Logic;



class InputFormatter implements IInputFormatter
{

    private $syntax = self::POSTGRES;

    function __construct($syntax = self::POSTGRES)
    {
        $this->syntax = $syntax;
    }

    public function toDoublePrecision(float $value): string
    {

        $result = '';

        switch ($this->syntax) {
            case self::POSTGRES:
                $result = var_export($value, true); // sprintf('%F',$value);
                break;
        }


        return $result;
    }

    public function toTimestampWithTimeZone(string $value): string
    {

        $result = '';

        $date = new \DateTime($value);

        $isNull = is_null($date);
        if(!$isNull){

            switch ($this->syntax) {

                case self::POSTGRES:
                    $result = $value;
                    break;
            }

        }

        return $result;
    }

    public function toText($value): string
    {

        $result = '';

        switch ($this->syntax) {
            case self::POSTGRES:
                $result = strval($value);
                break;
        }


        return $result;
    }

    public function toInteger($value): ?int
    {

        $result = null;

        switch ($this->syntax) {
            case self::POSTGRES:

                $isNull = is_null($value);
                if (!$isNull) {
                    $result = intval($value);
                }

                break;
        }


        return $result;
    }

    public function castFloat(string $placeholder): string
    {

        $cast = '';

        switch ($this->syntax) {
            case self::POSTGRES:
                $cast = 'CAST(' . $placeholder . ' AS DOUBLE PRECISION)';
                break;
        }

        return $cast;
    }

    public function castTimestamp(string $placeholder): string
    {

        $cast = '';

        switch ($this->syntax) {
            case self::POSTGRES:
                $cast = "to_timestamp($placeholder,'".self::TIMESTAMP_PATTERN."')";
                break;
        }

        return $cast;
    }

}
