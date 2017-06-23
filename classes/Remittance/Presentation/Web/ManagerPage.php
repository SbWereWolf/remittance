<?php

namespace Remittance\Presentation\Web;


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
use Remittance\BusinessLogic\Manager\Currency;
use Remittance\BusinessLogic\Manager\Rate;
use Remittance\BusinessLogic\Operator\Transfer;
use Remittance\Presentation\UserInput\InputArray;
use Remittance\Presentation\Web\Page\ManagerMenu;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\PhpRenderer;


class ManagerPage implements IRoute
{

    const MODULE_CURRENCY = 'currency';

    const ACTION_CURRENCY_EDIT = 'currency_edit';
    const ACTION_CURRENCY_SAVE = 'currency_save';
    const ACTION_CURRENCY_ADD = 'currency_add';
    const ACTION_CURRENCY_ENABLE = 'currency_enable';
    const ACTION_CURRENCY_DISABLE = 'currency_disable';

    const MODULE_RATE = 'rate';

    const ACTION_RATE_EDIT = 'rate_edit';
    const ACTION_RATE_SAVE = 'rate_save';
    const ACTION_RATE_ADD = 'rate_add';
    const ACTION_RATE_DEFAULT = 'rate_default';
    const ACTION_RATE_ENABLE = 'rate_enable';
    const ACTION_RATE_DISABLE = 'rate_disable';

    const RATE_SOURCE_CURRENCY_TITLE = 'source_code';
    const RATE_TARGET_CURRENCY_TITLE = 'target_code';

    const MODULE_VOLUME = 'volume';

    const ACTION_VOLUME_EDIT = 'volume_edit';
    const ACTION_VOLUME_ADD = 'volume_add';
    const ACTION_VOLUME_SAVE = 'volume_save';
    const ACTION_VOLUME_ENABLE = 'volume_enable';
    const ACTION_VOLUME_DISABLE = 'volume_disable';

    const MODULE_FEE = 'fee';

    const CURRENCY_TITLE = 'currency_code';

    const MODULE_SETTING = 'setting';

    const REFERENCES_LINKS = 'references_links';
    const SETTINGS_LINKS = 'settings_links';
    const SETTINGS_COMMON = 'setting_common';
    const CURRENCY_REFERENCE = 'currency_reference';
    const VOLUME_REFERENCE = 'account_reference';
    const RATE_REFERENCE = 'rate_reference';
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
        $response = $this->viewer->render($response, "manager/currency_list.php", [
            'currencies' => $currencies,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
            'menu' => $menu,
        ]);

        return $response;
    }

    public function currencyEdit(Request $request, Response $response, array $arguments)
    {

        $getArray = new InputArray($arguments);
        $id = $getArray->getIntegerValue(self::ID);

        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $named = $searcher->searchById($id);

        $currency = new Currency();
        $isSuccess = $currency->assumeNamedEntity($named);

        $actionLinks = $this->setEditCurrencyActions();

        $menu = $this->assembleManagerLinks();

        $response = $this->viewer->render($response, "manager/currency_edit.php", [
            'currency' => $currency,
            'menu' => $menu,
            'actionLinks' => $actionLinks,
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
        $response = $this->viewer->render($response, "manager/rate_list.php", [
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

    public function rateEdit(Request $request, Response $response, array $arguments)
    {

        $getArray = new InputArray($arguments);
        $id = $getArray->getIntegerValue(self::ID);

        $rate = new Rate();
        $isSuccess = $rate->assembleRate($id);

        $actionLinks = $this->setEditRateActions();

        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currencies = $searcher->searchCurrency();

        $menu = $this->assembleManagerLinks();

        $response = $this->viewer->render($response, "manager/rate_edit.php", [
            'rate' => $rate,
            'currencies' => $currencies,
            'menu' => $menu,
            'actionLinks' => $actionLinks,
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
        $response = $this->viewer->render($response, "manager/volume_list.php", [
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

    public function volumeEdit(Request $request, Response $response, array $arguments)
    {

        $getArray = new InputArray($arguments);
        $id = $getArray->getIntegerValue(self::ID);

        $searcher = new VolumeSearch();
        $volume = $searcher->searchById($id);

        $actionLinks = $this->setEditVolumeActions();

        $menu = $this->assembleManagerLinks();

        $searcher = new NamedEntitySearch(CurrencyRecord::TABLE_NAME);
        $currencies = $searcher->searchCurrency();

        $response = $this->viewer->render($response, "manager/volume_edit.php", [
            'volume' => $volume,
            'currencies' => $currencies,
            'menu' => $menu,
            'actionLinks' => $actionLinks,
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
            $editLink = $this->router->pathFor(
                self::ACTION_CURRENCY_EDIT,
                [self::ID => $id]);

            $actionLinks[$id][self::ACTION_CURRENCY_DISABLE] = $disableLink;
            $actionLinks[$id][self::ACTION_CURRENCY_ENABLE] = $enableLink;
            $actionLinks[$id][self::ACTION_CURRENCY_EDIT] = $editLink;
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
                $editLink = $this->router->pathFor(
                    self::ACTION_RATE_EDIT,
                    [self::ID => $id]);

                $actionLinks[$id][self::ACTION_RATE_DEFAULT] = $defaultLink;
                $actionLinks[$id][self::ACTION_RATE_ENABLE] = $enableLink;
                $actionLinks[$id][self::ACTION_RATE_DISABLE] = $disableLink;
                $actionLinks[$id][self::ACTION_RATE_EDIT] = $editLink;
            }
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
                    $editLink = $this->router->pathFor(
                        self::ACTION_VOLUME_EDIT,
                        [self::ID => $id]);

                    $actionLinks[$id][self::ACTION_VOLUME_ENABLE] = $enableLink;
                    $actionLinks[$id][self::ACTION_VOLUME_DISABLE] = $disableLink;
                    $actionLinks[$id][self::ACTION_VOLUME_EDIT] = $editLink;
                }

            }
        }

        return $actionLinks;
    }

    private function setEditVolumeActions(): array
    {
        $actionLinks = ICommon::EMPTY_ARRAY;

        $saveElementLink = $this->router->pathFor(
            self::ACTION_VOLUME_SAVE);
        $viewListLink = $this->router->pathFor(self::MODULE_VOLUME);

        $actionLinks[self::ACTION_VOLUME_SAVE] = $saveElementLink;
        $actionLinks[self::MODULE_VOLUME] = $viewListLink;


        return $actionLinks;
    }

    private function setEditCurrencyActions(): array
    {
        $actionLinks = ICommon::EMPTY_ARRAY;

        $saveElementLink = $this->router->pathFor(
            self::ACTION_CURRENCY_SAVE);
        $viewListLink = $this->router->pathFor(self::MODULE_CURRENCY);

        $actionLinks[self::ACTION_CURRENCY_SAVE] = $saveElementLink;
        $actionLinks[self::MODULE_CURRENCY] = $viewListLink;


        return $actionLinks;
    }

    private function setEditRateActions(): array
    {
        $actionLinks = ICommon::EMPTY_ARRAY;

        $saveElementLink = $this->router->pathFor(
            self::ACTION_RATE_SAVE);
        $viewListLink = $this->router->pathFor(self::MODULE_RATE);

        $actionLinks[self::ACTION_RATE_SAVE] = $saveElementLink;
        $actionLinks[self::MODULE_RATE] = $viewListLink;


        return $actionLinks;
    }

    /**
     * @return ManagerMenu
     */
    private function assembleManagerLinks(): ManagerMenu
    {
        $currencyLink = $this->router->pathFor(self::MODULE_CURRENCY);
        $volumeLink = $this->router->pathFor(self::MODULE_VOLUME);
        $rateLink = $this->router->pathFor(self::MODULE_RATE);
        $settingLink = $this->router->pathFor(self::MODULE_SETTING);
        $feeLink = $this->router->pathFor(self::MODULE_FEE);

        $menu = new ManagerMenu();

        $menu->currencyLink=$currencyLink;
        $menu->feeLink=$feeLink;
        $menu->rateLink=$rateLink;
        $menu->settingLink=$settingLink;
        $menu->volumeLink=$volumeLink;


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
        $response = $this->viewer->render($response, "manager/fee_list.php", [
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
