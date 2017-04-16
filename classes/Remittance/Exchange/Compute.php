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

        $isExists = !empty($exchangeRate->id);
        $outcome = 0;
        if ($isExists) {
            $outcome = $this->income * (1 - $exchangeRate->fee) * $exchangeRate->exchangeRate;
        }

        $result = round(floatval($outcome),2);

        return $result;
    }
}
