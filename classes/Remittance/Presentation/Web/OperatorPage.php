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
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\PhpRenderer;


class OperatorPage
{

    const ACTION_ACCOMPLISH = 'accomplish';
    const ACTION_ANNUL = 'annul';
    const ID = 'id';

    const DEAL_INCOME = 'income_amount';
    const DEAL_OUTCOME = 'outcome_amount';
    const DEAL_EMAIL = 'report_email';
    const FIO_TRANSFER = 'transfer_name';
    const ACCOUNT_TRANSFER = 'transfer_account';
    const FIO_RECEIVE = 'receive_name';
    const ACCOUNT_RECEIVE = 'receive_account';
    const DOCUMENT_NUMBER = 'document_number';
    const DOCUMENT_DATE = 'document_date';
    const INCOME_CURRENCY = 'income_account';
    const OUTCOME_CURRENCY = 'outcome_account';
    const TRANSFER_STATUS = 'transfer_status_id';
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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $arguments
     * @return Response
     */
    public function root(Request $request, Response $response, array $arguments)
    {

        $searcher = new TransferSearch();
        $transfers = $searcher->search();

        $transferView = $this->setTransfersView($transfers);
        $actionLinks = $this->setTransfersActions($transfers);

        $offset = 0;
        $limit = 0;
        $response = $this->viewer->render($response, "operator/operator.php", [
            'transferView' => $transferView,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
        ]);

        return $response;

    }

    /**
     * @param $transfers
     * @return array
     */
    private function setTransfersActions($transfers): array
    {
        $isValid = Common::isValidArray($transfers);

        $actionLinks = ICommon::EMPTY_ARRAY;
        if ($isValid) {

            foreach ($transfers as $transfer) {
                $isObject = $transfer instanceof TransferRecord;
                if ($isObject) {

                    $id = $transfer->id;

                    $accomplishLink = $this->router->pathFor(
                        self::ACTION_ACCOMPLISH,
                        [self::ID => $id]);
                    $annulLink = $this->router->pathFor(
                        self::ACTION_ANNUL,
                        [self::ID => $id]);

                    $actionLinks[$id][self::ACTION_ACCOMPLISH] = $accomplishLink;
                    $actionLinks[$id][self::ACTION_ANNUL] = $annulLink;
                }
            }

        }
        return $actionLinks;
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

                    $statusTitle = $transfer->getTransferStatusTitle();
                    $rowView[self::TRANSFER_STATUS] = $statusTitle;

                    $rowView[self::DEAL_EMAIL] = $transfer->dealEmail;
                    $rowView[self::FIO_TRANSFER] = $transfer->fioTransfer;
                    $rowView[self::ACCOUNT_TRANSFER] = $transfer->accountTransfer;
                    $rowView[self::INCOME_CURRENCY] = $transfer->incomeCurrency;
                    $rowView[self::DEAL_INCOME] = $transfer->dealIncome;
                    $rowView[self::FIO_RECEIVE] = $transfer->fioReceive;
                    $rowView[self::ACCOUNT_RECEIVE] = $transfer->accountReceive;
                    $rowView[self::OUTCOME_CURRENCY] = $transfer->outcomeCurrency;
                    $rowView[self::DEAL_OUTCOME] = $transfer->dealOutcome;
                    $rowView[self::STATUS_COMMENT] = $transfer->statusComment;
                    $rowView[self::STATUS_TIME] = $transfer->statusTime;
                    $rowView[self::AWAIT_NAME] = $transfer->fioAwait;
                    $rowView[self::AWAIT_ACCOUNT] = $transfer->accountAwait;
                    $rowView[self::PROCEED_ACCOUNT] = $transfer->accountProceed;
                    $rowView[self::PROCEED_NAME] = $transfer->fioProceed;

                    $transferView[] = $rowView;

                }
            }

        }
        return $transferView;
    }

}
