<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-07-28
 * Time: 18:57
 */

namespace Remittance\DataAccess\Logic;


interface IInputFormatter extends IDbFormatter
{

    public function toDoublePrecision(float $value): string;

    public function toTimestampWithTimeZone(string $value): string;

    public function castFloat(string $placeholder): string;

    public function castTimestamp(string $placeholder): string;
}
