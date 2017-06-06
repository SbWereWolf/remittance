<?php

namespace Remittance\Web;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\RateRecord;
use Remittance\DataAccess\Entity\TransferRecord;
use Remittance\DataAccess\Entity\VolumeRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Remittance\DataAccess\Search\RateSearch;
use Remittance\DataAccess\Search\TransferSearch;
use Remittance\DataAccess\Search\VolumeSearch;
use Remittance\Operator\Transfer;
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

    const MODULE_FEE = 'fee';

    const CURRENCY_TITLE = 'currency_code';

    const MODULE_SETTING = 'setting';

    const NAVIGATION_MENU = 'navigation_menu';

    const REFERENCES_LINKS = 'references_links';
    const SETTINGS_LINKS = 'settings_links';
    const SETTINGS_COMMON = 'settings_common';
    const CURRENCY_REFERENCE = 'currency_reference';
    const VOLUME_REFERENCE = 'accounts_reference';
    const RATES_REFERENCE = 'rates_reference';
    const FEE_REFERENCE = 'fee_reference';

    const DEAL_INCOME = 'income_amount';
    const DEAL_OUTCOME = 'outcome_amount';
    const DOCUMENT_NUMBER = 'document_number';
    const DOCUMENT_DATE = 'document_date';
    const INCOME_CURRENCY = 'income_account';
    const OUTCOME_CURRENCY = 'outcome_account';
    const STATUS_TIME = 'status_time';
    const AWAIT_NAME = 'await_name';
    const AWAIT_ACCOUNT = 'await_account';
    const PROCEED_ACCOUNT = 'proceed_account';
    const PROCEED_NAME = 'proceed_name';
    const FEE = 'fee';
    const BODY = 'body';

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
        $feeLink = $this->router->pathFor(self::MODULE_FEE);


        $menu = array(
            self::REFERENCES_LINKS => array(
                self::CURRENCY_REFERENCE => $currencyLink,
                self::VOLUME_REFERENCE => $volumeLink,
                self::RATES_REFERENCE => $rateLink,
                self::FEE_REFERENCE => $feeLink,
            ),
            self::SETTINGS_LINKS => array(
                self::SETTINGS_COMMON => $settingLink,
            ),
        );


        return $menu;
    }

    public function fee($request, $response, $arguments)
    {
        $menu = $this->assembleManagerLinks();

        $searcher = new TransferSearch();
        $transfers = $searcher->searchByStatus(Transfer::STATUS_ACCOMPLISH);

        $transferView = $this->setTransfersView($transfers);

        $offset = 0;
        $limit = 0;
        $response = $this->viewer->render($response, "manager/fee.php", [
            'transferView' => $transferView,
            'offset' => $offset,
            'limit' => $limit,
            'menu' => $menu,
        ]);

        return $response;
    }

    /**
     * @param $transfers
     * @return array
     */
    private function setTransfersView($transfers): array
    {
        $isValid = Common::isValidArray($transfers);

        $transferView = ICommon::EMPTY_ARRAY;
        if ($isValid) {

            foreach ($transfers as $transferCandidate) {
                $isInstance = $transferCandidate instanceof TransferRecord;
                if ($isInstance) {

                    $transferRecord = TransferRecord::adopt($transferCandidate);

                    $rowView[self::ID] = $transferRecord->id;

                    $transfer = new Transfer();
                    $transfer->assume($transferRecord);

                    $rowView[self::DOCUMENT_NUMBER] = $transfer->documentNumber;
                    $rowView[self::DOCUMENT_DATE] = $transfer->documentDate;
                    $rowView[self::INCOME_CURRENCY] = $transfer->incomeCurrency;
                    $rowView[self::DEAL_INCOME] = $transfer->dealIncome;
                    $rowView[self::OUTCOME_CURRENCY] = $transfer->outcomeCurrency;
                    $rowView[self::DEAL_OUTCOME] = $transfer->dealOutcome;
                    $rowView[self::STATUS_TIME] = $transfer->statusTime;
                    $rowView[self::AWAIT_NAME] = $transfer->fioAwait;
                    $rowView[self::AWAIT_ACCOUNT] = $transfer->accountAwait;
                    $rowView[self::PROCEED_ACCOUNT] = $transfer->accountProceed;
                    $rowView[self::PROCEED_NAME] = $transfer->fioProceed;
                    $rowView[self::FEE] = $transfer->fee;
                    $rowView[self::BODY] = $transfer->body;

                    $transferView[] = $rowView;

                }
            }

        }
        return $transferView;
    }

}
