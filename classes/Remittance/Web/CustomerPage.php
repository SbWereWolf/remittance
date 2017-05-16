<?php

namespace Remittance\Web;


use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\PhpRenderer;


class CustomerPage implements IPage
{
    const MODULE_ORDER = 'order';
    const ACTION_ORDER_ADD = 'order_add';

    const ACTION_COMPUTE = 'compute_exchange';

    private $viewer;
    private $router;

    public function __construct(PhpRenderer $viewer, Router $router)
    {
        $this->viewer = $viewer;
        $this->router = $router;
    }

    public function root(Request $request, Response $response, array $arguments)
    {
        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currencies = $searcher->searchCurrency();

        $actionLinks = $this->setCustomerActions();

        $response = $this->viewer->render($response,
            "remittance/remittance.php",
            ['currencies' => $currencies,
                'actionLinks' => $actionLinks]);

        return $response;
    }

    /**
     * @return array массив uri для действий потребителя
     */
    private function setCustomerActions(): array
    {
        $actionLinks[self::ACTION_ORDER_ADD] = $this->router->pathFor(self::ACTION_ORDER_ADD);
        $actionLinks[self::ACTION_COMPUTE] = $this->router->pathFor(self::ACTION_COMPUTE);


        return $actionLinks;
    }

}
