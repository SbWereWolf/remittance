<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-07-28
 * Time: 18:40
 */

namespace Remittance\DataAccess\Logic;



class OutputFormatter implements IOutputFormatter
{

    private $syntax = self::POSTGRES;

    function __construct($syntax = self::POSTGRES)
    {
        $this->syntax = $syntax;
    }

    public function castTimestampToString(string $columnName): string
    {

        $cast = '';

        switch ($this->syntax) {
            case self::POSTGRES:
                $cast = "to_char($columnName,'".self::TIMESTAMP_PATTERN."')";
                break;
        }

        return $cast;
    }

}
