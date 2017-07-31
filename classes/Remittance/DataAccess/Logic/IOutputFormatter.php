<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-07-28
 * Time: 18:57
 */

namespace Remittance\DataAccess\Logic;


interface IOutputFormatter extends IDbFormatter
{

    public function castTimestampToString(string $columnName): string;
}
