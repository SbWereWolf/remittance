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
    const ROOT = '/';
    const PATH_SYMBOL = '/';

    const MODULE_CURRENCY = 'currency';

    const ACTION_CURRENCY_ADD = 'add';
    const ACTION_CURRENCY_ENABLE = 'currency_enable';
    const ACTION_CURRENCY_DISABLE = 'currency_disable';

    const MODULE_ACCOUNT = 'account';
    const MODULE_RATE = 'rate';
    const MODULE_SETTING = 'setting';

    const NAVIGATION_MENU = 'navigation_menu';

    const REFERENCES_LINKS = 'references_links';
    const SETTINGS_LINKS = 'settings_links';
    const SETTINGS_COMMON = 'settings_common';
    const CURRENCY_REFERENCE = 'currency_reference';
    const ACCOUNTS_REFERENCE = 'accounts_reference';
    const RATES_REFERENCE = 'rates_reference';

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

        $menuRoot = $this->router->pathFor(self::ROOT);
        $currencyLink = $this->router->pathFor(self::MODULE_CURRENCY);
        $accountLink = $this->router->pathFor(self::MODULE_ACCOUNT);
        $rateLink = $this->router->pathFor(self::MODULE_RATE);
        $settingLink = $this->router->pathFor(self::MODULE_SETTING);
        $menu = array(
            self::NAVIGATION_MENU => array(
                self::ROOT => $menuRoot,
            ),
            self::REFERENCES_LINKS => array(
                self::CURRENCY_REFERENCE => $currencyLink,
                self::ACCOUNTS_REFERENCE => $accountLink,
                self::RATES_REFERENCE => $rateLink,
            ),
            self::SETTINGS_LINKS => array(
                self::SETTINGS_COMMON => $settingLink,
            ),
        );

        $response = $this->viewer->render($response, "manager/menu.php", [
            'menu' => $menu,
        ]);

        return $response;
    }

    public function currency(Request $request, Response $response, array $arguments)
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
                    self::ACTION_CURRENCY_DISABLE,
                    [self::ID => $id]);
                $enableLink = $this->router->pathFor(
                    self::ACTION_CURRENCY_ENABLE,
                    [self::ID => $id]);

                $actionLinks[$id][self::ACTION_CURRENCY_DISABLE] = $disableLink;
                $actionLinks[$id][self::ACTION_CURRENCY_ENABLE] = $enableLink;
            }
        }

        $offset = 0;
        $limit = 0;
        $response = $this->viewer->render($response, "manager/currency.php", [
            'currencies' => $currencies,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
        ]);

        return $response;
    }

}
