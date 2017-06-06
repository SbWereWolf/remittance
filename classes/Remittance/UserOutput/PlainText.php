<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-06-06
 * Time: 13:00
 */

namespace Remittance\UserOutput;


use Remittance\Core\Common;

class PlainText implements IPlainText
{
    private $empty = '';

    function __construct(string $empty = self::EMPTY_VALUE)
    {
        $this->empty = $empty;
    }

    public function printElement(&$array, $key): string
    {

        $result = Common::setIfExists($key,$array,$this->empty);

        return $result;
    }

}
