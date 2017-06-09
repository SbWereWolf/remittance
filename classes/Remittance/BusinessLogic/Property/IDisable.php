<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-06-09
 * Time: 15:26
 */

namespace Remittance\BusinessLogic\Property;


use Remittance\DataAccess\Column\IHidden;

interface IDisable
{

    /** @var string значение для поднятого флага "отключен" */
    const DEFINE_AS_DISABLE = IHidden::DEFINE_AS_HIDDEN;
    /** @var string значение для снятого флага "отключен" */
    const DEFINE_AS_ENABLE = IHidden::DEFINE_AS_NOT_HIDDEN;
    /** @var string значение по умолчанию для признака "отключен" */
    const DEFAULT_IS_DISABLE = IHidden::DEFINE_AS_NOT_HIDDEN;
}
