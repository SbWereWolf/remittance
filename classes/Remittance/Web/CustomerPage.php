<?php

namespace Remittance\Web;


use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;


class CustomerPage
{
    private $viewer;

    public function __construct(PhpRenderer $viewer)
    {
        $this->viewer = $viewer;
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
        $currencies = array();
        if ($isValid) {

            foreach ($records as $record) {
                $asArray = $record->toEntity();
                $currency = new CurrencyRecord();
                $currency->setByNamedValue($asArray);
                $currencies[] = $currency;

            }
        }


        $response = $this->viewer->render($response,
            "remittance/remittance.php",
            ['currencies' => $currencies]);

        return $response;
    }

}
