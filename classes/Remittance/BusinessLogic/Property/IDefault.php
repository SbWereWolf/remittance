<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-06-09
 * Time: 14:45
 */

namespace Remittance\BusinessLogic\Property;

interface IDefault
{
    /** @var string значение для поднятого флага "использовать по умолчанию" */
    const DEFINE_AS_DEFAULT = \Remittance\DataAccess\Column\IDefault::DEFAULT_IS_DEFAULT;
    /** @var string значение для снятого флага "использовать по умолчанию" */
    const DEFINE_AS_NOT_DEFAULT = \Remittance\DataAccess\Column\IDefault::DEFINE_AS_NOT_DEFAULT;
    /** @var string значение по умолчанию для флага "использовать по умолчанию" */
    const DEFAULT_IS_DEFAULT = \Remittance\DataAccess\Column\IDefault::DEFINE_AS_NOT_DEFAULT;

}
