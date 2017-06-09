<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-06-09
 * Time: 14:45
 */

namespace Remittance\DataAccess\Column;


interface IHidden
{
    /** @var string значение для поднятого флага "является скрытым" */
    const DEFINE_AS_HIDDEN = true;
    /** @var string значение для поднятого флага "является скрытым" */
    const DEFINE_AS_NOT_HIDDEN = false;
    /** @var string значение по умолчанию для флага "является скрытым" */
    const DEFAULT_IS_HIDDEN = self::DEFINE_AS_NOT_HIDDEN;

    /** @var string колонка признака "является скрытым" */
    const IS_HIDDEN = 'is_hidden';
}
