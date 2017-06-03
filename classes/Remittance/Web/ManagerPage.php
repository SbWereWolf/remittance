<?php

namespace Remittance\Web;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\RateRecord;
use Remittance\DataAccess\Entity\VolumeRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Remittance\DataAccess\Search\RateSearch;
use Remittance\DataAccess\Search\VolumeSearch;
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
    const ACTION_RATE_EDIT = 'rate_edit';
    const ACTION_RATE_DEFAULT = 'rate_default';
    const ACTION_RATE_ENABLE = 'rate_enable';
    const ACTION_RATE_DISABLE = 'rate_disable';

    const RATE_SOURCE_CURRENCY_TITLE = 'source_code';
    const RATE_TARGET_CURRENCY_TITLE = 'target_code';

    const MODULE_VOLUME = 'volume';
    const ACTION_VOLUME_ADD = 'volume_add';
    const ACTION_VOLUME_EDIT = 'volume_edit';
    const ACTION_VOLUME_ENABLE = 'volume_enable';
    const ACTION_VOLUME_DISABLE = 'volume_disable';

    const CURRENCY_TITLE = 'currency_code';

    const MODULE_SETTING = 'setting';

    const NAVIGATION_MENU = 'navigation_menu';

    const REFERENCES_LINKS = 'references_links';
    const SETTINGS_LINKS = 'settings_links';
    const SETTINGS_COMMON = 'settings_common';
    const CURRENCY_REFERENCE = 'currency_reference';
    const VOLUME_REFERENCE = 'accounts_reference';
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

        $menu = $this->assembleManagerLinks();

        $response = $this->viewer->render($response, "manager/start.php", [
            'menu' => $menu,
        ]);

        return $response;
    }

    public function currency(Request $request, Response $response, array $arguments)
    {
        $menu = $this->assembleManagerLinks();

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
            'menu' => $menu,
        ]);

        return $response;
    }

    public function rate(Request $request, Response $response, array $arguments)
    {
        $menu = $this->assembleManagerLinks();

        $searcher = new RateSearch();
        $rates = $searcher->search();

        $actionLinks = $this->setRateActions($rates);

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
            'menu' => $menu,
        ]);

        return $response;
    }

    public function volume(Request $request, Response $response, array $arguments)
    {
        $menu = $this->assembleManagerLinks();

        $searcher = new VolumeSearch();
        $volumes = $searcher->search();
        $actionLinks = $this->setVolumeActions($volumes);

        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currencies = $searcher->searchCurrency();
        $isValid = Common::isValidArray($currencies);
        $currencyTitles = ICommon::EMPTY_ARRAY;
        if ($isValid) {
            foreach ($volumes as $volumeCandidate) {
                $volume = VolumeRecord::adopt($volumeCandidate);

                $currencySearcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
                $currency = $currencySearcher->searchById($volume->currencyId);
                $isCurrencyFound = !empty($currency->id);

                $isSuccess = $isCurrencyFound;
                if ($isSuccess) {
                    $currencyTitles[$volume->id][self::CURRENCY_TITLE] = $currency->title;
                }
            }
        }

        $offset = 0;
        $limit = 0;
        $response = $this->viewer->render($response, "manager/volume.php", [
            'volumes' => $volumes,
            'currencies' => $currencies,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
            'currencyTitles' => $currencyTitles,
            'menu' => $menu,
        ]);

        return $response;
    }

    /**
     * @param $currencies
     * @return array
     */
    private function setCurrencyActions($currencies): array
    {
        $actionLinks[ManagerPage::ACTION_CURRENCY_ADD] = $this->router->pathFor(self::ACTION_CURRENCY_ADD);

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
     * @param $volumes
     * @return array|mixed
     * @internal param $actionLinks
     */
    private function setVolumeActions(array $volumes): array
    {
        $actionLinks = ICommon::EMPTY_ARRAY;

        $addVolumeLink = $this->router->pathFor(
            self::ACTION_VOLUME_ADD);
        $actionLinks[self::ACTION_VOLUME_ADD] = $addVolumeLink;

        $isValid = Common::isValidArray($volumes);

        if ($isValid) {
            foreach ($volumes as $volumeCandidate) {

                $isObject = $volumeCandidate instanceof VolumeRecord;
                if ($isObject) {
                    $volume = VolumeRecord::adopt($volumeCandidate);

                    $id = $volume->id;

                    $enableLink = $this->router->pathFor(
                        self::ACTION_VOLUME_ENABLE,
                        [self::ID => $id]);
                    $disableLink = $this->router->pathFor(
                        self::ACTION_VOLUME_DISABLE,
                        [self::ID => $id]);

                    $actionLinks[$id][self::ACTION_VOLUME_ENABLE] = $enableLink;
                    $actionLinks[$id][self::ACTION_VOLUME_DISABLE] = $disableLink;
                }

            }
        }

        return $actionLinks;
    }

    /**
     * @param $rates
     * @return array|mixed
     * @internal param $actionLinks
     */
    private function setRateActions(array $rates): array
    {
        $addRateLink = $this->router->pathFor(
            self::ACTION_RATE_ADD);

        $actionLinks[self::ACTION_RATE_ADD] = $addRateLink;

        $isValid = Common::isValidArray($rates);
        if ($isValid) {
            foreach ($rates as $rate) {

                $id = $rate->id;

                $defaultLink = $this->router->pathFor(
                    self::ACTION_RATE_DEFAULT,
                    [self::ID => $id]);
                $enableLink = $this->router->pathFor(
                    self::ACTION_RATE_ENABLE,
                    [self::ID => $id]);
                $disableLink = $this->router->pathFor(
                    self::ACTION_RATE_DISABLE,
                    [self::ID => $id]);

                $actionLinks[$id][self::ACTION_RATE_DEFAULT] = $defaultLink;
                $actionLinks[$id][self::ACTION_RATE_ENABLE] = $enableLink;
                $actionLinks[$id][self::ACTION_RATE_DISABLE] = $disableLink;
            }
        }

        return $actionLinks;
    }

    /**
     * @return array
     */
    private function assembleManagerLinks(): array
    {
        $currencyLink = $this->router->pathFor(self::MODULE_CURRENCY);
        $volumeLink = $this->router->pathFor(self::MODULE_VOLUME);
        $rateLink = $this->router->pathFor(self::MODULE_RATE);
        $settingLink = $this->router->pathFor(self::MODULE_SETTING);
        $menu = array(
            self::REFERENCES_LINKS => array(
                self::CURRENCY_REFERENCE => $currencyLink,
                self::VOLUME_REFERENCE => $volumeLink,
                self::RATES_REFERENCE => $rateLink,
            ),
            self::SETTINGS_LINKS => array(
                self::SETTINGS_COMMON => $settingLink,
            ),
        );


        return $menu;
    }

}
