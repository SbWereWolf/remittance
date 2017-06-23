<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 06.04.2017
 * Time: 19:33
 */

namespace Remittance\Presentation\Web;


use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\TransferRecord;
use Remittance\DataAccess\Search\TransferSearch;
use Remittance\BusinessLogic\Operator\Transfer;
use Remittance\Presentation\UserInput\InputArray;
use Remittance\Presentation\Web\Page\OperatorMenu;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\PhpRenderer;


class OperatorPage implements IRoute
{
    const DOCUMENTS_LINKS = 'documents_links';

    const MODULE_TRANSFER = 'transfer';

    const TRANSFER_DOCUMENTS = 'transfer_documents';

    const ACTION_TRANSFER_ACCOMPLISH = 'transfer_accomplish';
    const ACTION_TRANSFER_ANNUL = 'transfer_annul';
    const ACTION_TRANSFER_EDIT = 'transfer_edit';

    const ID = 'id';

    const DEAL_INCOME = 'income_amount';
    const DEAL_OUTCOME = 'outcome_amount';
    const DEAL_EMAIL = 'report_email';
    const NAME_TRANSFER = 'transfer_name';
    const ACCOUNT_TRANSFER = 'transfer_account';
    const NAME_RECEIVE = 'receive_name';
    const ACCOUNT_RECEIVE = 'receive_account';
    const DOCUMENT_NUMBER = 'document_number';
    const DOCUMENT_DATE = 'document_date';
    const INCOME_CURRENCY = 'income_account';
    const OUTCOME_CURRENCY = 'outcome_account';
    const TRANSFER_STATUS = 'transfer_status';
    const STATUS_COMMENT = 'status_comment';
    const STATUS_TIME = 'status_time';
    const AWAIT_NAME = 'await_name';
    const AWAIT_ACCOUNT = 'await_account';
    const PROCEED_ACCOUNT = 'proceed_account';
    const PROCEED_NAME = 'proceed_name';


    private $router;
    private $viewer;

    public function __construct(PhpRenderer $viewer, Router $router)
    {
        $this->router = $router;
        $this->viewer = $viewer;
    }

    public function root(Request $request, Response $response, array $arguments)
    {

        $menu = $this->assembleOperatorLinks();

        $response = $this->viewer->render($response, "operator/start.php", [
            'menu' => $menu,
        ]);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $arguments
     * @return Response
     */
    public function transfer(Request $request, Response $response, array $arguments)
    {

        $searcher = new TransferSearch();
        $transfers = $searcher->search();

        $transferView = $this->setRemittanceView($transfers);
        $actionLinks = $this->setTransferListActions($transfers);

        $menu = $this->assembleOperatorLinks();

        $offset = 0;
        $limit = 0;
        $response = $this->viewer->render($response, "operator/transfer_list.php", [
            'transferView' => $transferView,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
            'menu' => $menu,
        ]);

        return $response;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $arguments
     * @return Response
     */
    public function transferEdit(Request $request, Response $response, array $arguments)
    {
        $getArray = new InputArray($arguments);
        $id = $getArray->getIntegerValue(self::ID);

        $searcher = new TransferSearch();
        $transfer = $searcher->searchById($id);

        $actionLinks = $this->setTransferActions($transfer);

        $transferView = $this->setTransferView($transfer);

        $menu = $this->assembleOperatorLinks();

        $response = $this->viewer->render($response, "operator/transfer_edit.php", [
            'menu' => $menu,
            'transferView' => $transferView,
            'actionLinks' => $actionLinks,
        ]);

        return $response;

    }

    /**
     * @param $transfers
     * @return array
     */
    private function setRemittanceView($transfers): array
    {
        $isValid = Common::isValidArray($transfers);

        $remittanceView = ICommon::EMPTY_ARRAY;
        if ($isValid) {

            foreach ($transfers as $transferCandidate) {

                $transferView = $this->setTransferView($transferCandidate);

                $isValid = Common::isValidArray($transferView);
                if($isValid){
                    $remittanceView[] = $transferView;
                }
            }

        }
        return $remittanceView;
    }

    /**
     * @return OperatorMenu
     */
    private function assembleOperatorLinks(): OperatorMenu
    {
        $transferLink = $this->router->pathFor(self::MODULE_TRANSFER);

        $menu = new OperatorMenu();

        $menu->transferLink=$transferLink;


        return $menu;
    }

    /**
     * @param $transfers
     * @return array|mixed
     * @internal param $actionLinks
     */
    private function setTransferListActions(array $transfers): array
    {
        $actionLinks = ICommon::EMPTY_ARRAY;

        $isValid = Common::isValidArray($transfers);

        if ($isValid) {
            foreach ($transfers as $transferCandidate) {

                $isObject = $transferCandidate instanceof TransferRecord;
                if ($isObject) {
                    $transfer = TransferRecord::adopt($transferCandidate);

                    $id = $transfer->id;

                    $editLink = $this->router->pathFor(
                        self::ACTION_TRANSFER_EDIT,
                        [self::ID => $id]);

                    $actionLinks[$id][self::ACTION_TRANSFER_EDIT] = $editLink;
                }

            }
        }

        return $actionLinks;
    }

    /**
     * @param $transfer TransferRecord
     * @return array
     */
    private function setTransferActions(TransferRecord $transfer): array
    {
        $actionLinks = ICommon::EMPTY_ARRAY;

        $id = $transfer->id;

        $annulLink = $this->router->pathFor(
            self::ACTION_TRANSFER_ANNUL,
            [self::ID => $id]);
        $accomplishLink = $this->router->pathFor(
            self::ACTION_TRANSFER_ACCOMPLISH,
            [self::ID => $id]);

        $actionLinks[$id][self::ACTION_TRANSFER_ANNUL] = $annulLink;
        $actionLinks[$id][self::ACTION_TRANSFER_ACCOMPLISH] = $accomplishLink;

        return $actionLinks;
    }

    /**
     * @param $transferCandidate
     * @return array
     * @internal param $rowView
     * @internal param $transferView
     */
    private function setTransferView($transferCandidate): array
    {
        $rowView = Common::EMPTY_ARRAY;

        $isInstance = $transferCandidate instanceof TransferRecord;
        if ($isInstance) {

            $transferRecord = TransferRecord::adopt($transferCandidate);

            $rowView[self::ID] = $transferRecord->id;

            $transfer = new Transfer();
            $transfer->assume($transferRecord);

            $rowView[self::DOCUMENT_NUMBER] = $transfer->documentNumber;
            $rowView[self::DOCUMENT_DATE] = $transfer->documentDate;

            $statusTitle = $transfer->getTransferStatusTitle();
            $rowView[self::TRANSFER_STATUS] = $statusTitle;

            $rowView[self::DEAL_EMAIL] = $transfer->dealEmail;
            $rowView[self::NAME_TRANSFER] = $transfer->fioTransfer;
            $rowView[self::ACCOUNT_TRANSFER] = $transfer->accountTransfer;
            $rowView[self::INCOME_CURRENCY] = $transfer->incomeCurrency;
            $rowView[self::DEAL_INCOME] = $transfer->dealIncome;
            $rowView[self::NAME_RECEIVE] = $transfer->fioReceive;
            $rowView[self::ACCOUNT_RECEIVE] = $transfer->accountReceive;
            $rowView[self::OUTCOME_CURRENCY] = $transfer->outcomeCurrency;
            $rowView[self::DEAL_OUTCOME] = $transfer->dealOutcome;
            $rowView[self::STATUS_COMMENT] = $transfer->statusComment;
            $rowView[self::STATUS_TIME] = $transfer->statusTime;
            $rowView[self::AWAIT_NAME] = $transfer->fioAwait;
            $rowView[self::AWAIT_ACCOUNT] = $transfer->accountAwait;
            $rowView[self::PROCEED_ACCOUNT] = $transfer->accountProceed;
            $rowView[self::PROCEED_NAME] = $transfer->fioProceed;

        }
        return $rowView;
    }

}
