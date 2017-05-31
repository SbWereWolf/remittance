<?php

namespace Remittance\Exchange;


use Remittance\DataAccess\Search\RateSearch;

class Compute
{
    private $source;
    private $income;
    private $target;

    public function __construct(string $source, string $target, float $income)
    {
        $this->source = $source;
        $this->income = $income;
        $this->target = $target;
    }

    public function precomputation():float
    {

        $searcher = new RateSearch();
        $exchangeRate = $searcher->searchExchangeRate($this->source, $this->target);

        $result = NAN;
        $isExists = !empty($exchangeRate->id);
        if ($isExists) {
            $outcome = $this->calculate($exchangeRate->fee,$exchangeRate->exchangeRate);
            $result = round($outcome ,2);
        }

        return $result;
    }

    public function calculate(float $fee, float $rate):float
    {

        $outcome = $this->income * (1 - $fee) * $rate;
        $result = round(floatval($outcome),2);

        return $result;
    }
}
