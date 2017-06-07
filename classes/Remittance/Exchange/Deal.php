<?php

namespace Remittance\Exchange;


use const NAN;
use Remittance\DataAccess\Search\RateSearch;

class Deal
{
    private $source = '';
    private $income = 0;
    private $target = '';

    public $feeAmount = NAN;
    public $body = NAN;
    public $effectiveRatio = NAN;
    public $outcome = NAN;

    public function __construct(string $source, string $target, float $income)
    {
        $this->source = $source;
        $this->income = $income;
        $this->target = $target;
    }

    public function precomputation(): bool
    {

        $searcher = new RateSearch();
        $exchangeRate = $searcher->searchExchangeRate($this->source, $this->target);

        $computer = new Compute(NAN, NAN, NAN);
        $isExists = !empty($exchangeRate->id);
        $result = false;
        if ($isExists) {
            $computer = new Compute($this->income, $exchangeRate->fee, $exchangeRate->ratio);
            $computer->calculate();

            $result = true;
        }

        if ($result) {
            $this->feeAmount = $computer->feeAmount;
            $this->body = $computer->body;
            $this->outcome = $computer->outcome;
        }

        return $result;

    }
}
