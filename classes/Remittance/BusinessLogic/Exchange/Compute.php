<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-06-05
 * Time: 18:40
 */

namespace Remittance\BusinessLogic\Exchange;


class Compute
{
    private $income = NAN ;
    private $feeRatio = NAN ;
    public $feeAmount = NAN ;
    public $body = NAN ;
    private $rate = NAN ;
    public $outcome = NAN ;


    function __construct(float $income, float $feeRatio, float $rate)
    {
        $this->income = $income;
        $this->feeRatio = $feeRatio;
        $this->rate = $rate;
    }

    public function calculate():bool
    {

        $this->feeAmount = $this->income * $this->feeRatio;
        $this->body = $this->income - $this->feeAmount;
        $this->outcome = $this->body * $this->rate;

        return true;

    }
}
