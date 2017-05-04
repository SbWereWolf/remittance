<?php

namespace Remittance\Web;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\RateRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Remittance\DataAccess\Search\RateSearch;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\PhpRenderer;


class ManagerPage implements IPage
{

    const MODULE_CURRENCY = 'currency';

    const ACTION_CURRENCY_ADD = 'currency_add';
    const ACTION_CURRENCY_ENABLE = 'currency_enable';
    const ACTION_CURRENCY_DISABLE = 'currency_disable';

    const MODULE_RATE = 'rate';
    const ACTION_RATE_ADD = 'rate_add';
    const ACTION_RATE_SAVE = 'rate_save';
    const ACTION_RATE_DEFAULT = 'rate_default';
    const ACTION_RATE_ENABLE = 'rate_enable';
    const ACTION_RATE_DISABLE = 'rate_disable';

    const RATE_SOURCE_CURRENCY_TITLE = 'source_code';
    const RATE_TARGET_CURRENCY_TITLE = 'target_code';

    const MODULE_ACCOUNT = 'account';
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

        $response = $this->viewer->render($response, "manager/start.php", [
            'menu' => $menu,
        ]);

        return $response;
    }

    public function currency(Request $request, Response $response, array $arguments)
    {
        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);

        $currencies = $searcher->searchCurrency();
        $actionLinks = $this->setCurrencyActions($currencies);

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

    public function rate(Request $request, Response $response, array $arguments)
    {
        $searcher = new RateSearch();
        $rates = $searcher->search();

        $isValid = Common::isValidArray($rates);
        $actionLinks = ICommon::EMPTY_ARRAY;
        if ($isValid) {
            $actionLinks = $this->setRateActions($rates);
        }

        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currencies = $searcher->searchCurrency();
        $isValid = Common::isValidArray($currencies);
        $currencyTitles = ICommon::EMPTY_ARRAY;
        if ($isValid) {
            foreach ($rates as $rateCandidate) {
                $rate = RateRecord::adopt($rateCandidate);

                $source = $searcher->searchById($rate->sourceCurrencyId);
                $isSourceFound = !empty($source->id);

                $target = $searcher->searchById($rate->targetCurrencyId);
                $isTargetFound = !empty($target->id);

                $isSuccess = $isSourceFound && $isTargetFound;
                if ($isSuccess) {
                    $currencyTitles[$rate->id][self::RATE_SOURCE_CURRENCY_TITLE] = $source->title;
                    $currencyTitles[$rate->id][self::RATE_TARGET_CURRENCY_TITLE] = $target->title;
                }
            }
        }

        $offset = 0;
        $limit = 0;
        $response = $this->viewer->render($response, "manager/rate.php", [
            'rates' => $rates,
            'currencies' => $currencies,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
            'currencyTitles' => $currencyTitles,
        ]);

        return $response;
    }

    /**
     * @param $currencies
     * @return array
     */
    private function setCurrencyActions($currencies): array
    {
        $actionLinks = ICommon::EMPTY_ARRAY;
        foreach ($currencies as $currency) {
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
        return $actionLinks;
    }

    /**
     * @param $rates
     * @return array|mixed
     * @internal param $actionLinks
     */
    private function setRateActions($rates): array
    {
        $actionLinks = ICommon::EMPTY_ARRAY;
        foreach ($rates as $rate) {

            $id = $rate->id;

            $saveLink = $this->router->pathFor(
                self::ACTION_RATE_SAVE,
                [self::ID => $id]);
            $defaultLink = $this->router->pathFor(
                self::ACTION_RATE_DEFAULT,
                [self::ID => $id]);
            $enableLink = $this->router->pathFor(
                self::ACTION_RATE_ENABLE,
                [self::ID => $id]);
            $disableLink = $this->router->pathFor(
                self::ACTION_RATE_DISABLE,
                [self::ID => $id]);

            $actionLinks[$id][self::ACTION_RATE_SAVE] = $saveLink;
            $actionLinks[$id][self::ACTION_RATE_DEFAULT] = $defaultLink;
            $actionLinks[$id][self::ACTION_RATE_ENABLE] = $enableLink;
            $actionLinks[$id][self::ACTION_RATE_DISABLE] = $disableLink;
        }
        return $actionLinks;
    }

}
