<?php

namespace Remittance\Web;


use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;


class CustomerPage implements IPage
{
    const MODULE_ORDER = 'order';
    const ACTION_ORDER_ADD = 'add';

    private $viewer;

    public function __construct(PhpRenderer $viewer)
    {
        $this->viewer = $viewer;
    }

    public function root(Request $request, Response $response, array $arguments)
    {
        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currencies = $searcher->searchCurrency();

        $response = $this->viewer->render($response,
            "remittance/remittance.php",
            ['currencies' => $currencies]);

        return $response;
    }

}
