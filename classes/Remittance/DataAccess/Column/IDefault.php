<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-06-09
 * Time: 14:45
 */

namespace Remittance\DataAccess\Column;


interface IDefault
{
    /** @var string значение для поднятого флага "использовать по умолчанию" */
    const DEFINE_AS_DEFAULT = true;
    /** @var string значение для снятого флага "использовать по умолчанию" */
    const DEFINE_AS_NOT_DEFAULT = false;
    /** @var string значение по умолчанию для флага "использовать по умолчанию" */
    const DEFAULT_IS_DEFAULT = self::DEFINE_AS_NOT_DEFAULT;

    const IS_DEFAULT = 'is_default';

}
