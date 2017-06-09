<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-06-06
 * Time: 14:30
 */

namespace Remittance\Presentation\UserOutput;


class JsonFloat implements IJsonFloat
{

    private $raw;
    public $value;

    function __construct(float $raw)
    {
        $this->raw = $raw;
    }

    /** Преобразует не определённые значения и значения бесконечности в текст
     * @return mixed
     */
    public function prepare()
    {

        $isNan = is_nan($this->raw);

        if ($isNan) {
            $this->value = 'NAN';
        }

        $isInfinite = false;
        if (!$isNan) {
            $isInfinite = is_infinite($this->raw);
        }
        if ($isInfinite) {
            $this->value = 'INF';
        }

        if(!$isNan && !$isInfinite){
            $this->value = $this->raw;
        }
    }
}
