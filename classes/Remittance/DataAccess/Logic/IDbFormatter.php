<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-07-28
 * Time: 18:57
 */

namespace Remittance\DataAccess\Logic;


interface IDbFormatter
{
    const POSTGRES = 1;
    const TIMESTAMP_PATTERN = 'YYYY-MM-DD"T"HH24:MI:SS.US"Z"';
}
