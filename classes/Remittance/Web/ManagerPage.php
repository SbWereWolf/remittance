<?php

namespace Remittance\Web;


use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\PhpRenderer;


class ManagerPage
{
    const ACTION_ENABLE = 'enable';
    const ACTION_DISABLE = 'disable';
    const ID = 'id';

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
        $records = $searcher->search();

        $isSet = isset($records);
        $isArray = false;
        $isContain = false;
        if ($isSet) {
            $isArray = is_array($records);
            $isContain = count($records) > 0;
        }

        $isValid = $isArray && $isContain;
        $actionLinks = array();
        $currencies = array();
        if ($isValid) {

            foreach ($records as $record) {
                $asArray = $record->toEntity();
                $currency = new CurrencyRecord();
                $currency->setByNamedValue($asArray);
                $currencies[] = $currency;

                $id = $currency->id;

                $disableLink = $this->router->pathFor(
                    self::ACTION_DISABLE,
                    [self::ID => $id]);
                $enableLink = $this->router->pathFor(
                    self::ACTION_ENABLE,
                    [self::ID => $id]);

                $actionLinks[$id][self::ACTION_DISABLE] = $disableLink;
                $actionLinks[$id][self::ACTION_ENABLE] = $enableLink;
            }
        }

        $offset = 0;
        $limit = 0;
        $response = $this->viewer->render($response, "manager/manager.php", [
            'currencies' => $currencies,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
        ]);

        return $response;
    }

}
