<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 05.04.2017
 * Time: 2:23
 */

namespace Remittance\Operator;

use Remittance\Customer\Order;
use Remittance\DataAccess\Entity\TransferRecord;


class Transfer
{
    const STATUS_RECEIVED = 0;
    const ACTION_ACCOMPLISH = 'accomplish';

    public $dealIncome = 0;
    public $dealOutcome = 0;
    public $dealEmail = '';
    public $fioTransfer = '';
    public $accountTransfer = '';
    public $fioReceive = '';
    public $accountReceive = '';

    public $documentNumber = '';
    public $documentDate = '';
    public $incomeAccount = '';
    public $outcomeAccount = '';
    public $status = self::STATUS_RECEIVED;
    public $statusComment = '';
    public $statusTime = '';

    public function add(Order $orderDetail):bool
    {

        $record = new TransferRecord();

        $isSuccess = $record->addEntity();

        if ($isSuccess) {
            $record->isHidden = TransferRecord::DEFINE_AS_NOT_HIDDEN;

            $record->documentNumber = $record->id;
            $record->documentDate = date('Ymd');
            $record->reportEmail = $orderDetail->dealEmail;

            $record->incomeAccount = $orderDetail->dealSource;
            $record->incomeAmount = $orderDetail->dealIncome;
            $record->outcomeAccount = $orderDetail->dealTarget;
            $record->outcomeAmount = $orderDetail->dealOutcome;

            $record->transferAccount = $orderDetail->accountTransfer;
            $record->receiveName = $orderDetail->fioReceive;
            $record->receiveAccount = $orderDetail->accountReceive;
            $record->transferName = $orderDetail->fioTransfer;

            $record->status = $this->status;
            $record->statusComment = 'принята заявка с сайта';
            $record->statusTime = date('Ymd h:i:s');

            $isSuccess = $record->mutateEntity();
        }

        if ($isSuccess) {

            $this->fioTransfer = $record->transferName;
            $this->accountTransfer = $record->transferAccount;
            $this->accountReceive = $record->receiveAccount;
            $this->dealEmail = $record->reportEmail;
            $this->dealIncome = $record->incomeAmount;
            $this->dealOutcome = $record->outcomeAmount;
            $this->documentDate = $record->documentDate;
            $this->fioReceive = $record->receiveName;
            $this->incomeAccount = $record->incomeAccount;
            $this->outcomeAccount = $record->outcomeAccount;
            $this->documentNumber = $record->documentNumber;
            $this->status = $record->status;
            $this->statusComment = $record->statusComment;
            $this->statusTime = $record->statusTime;

        }

        return $isSuccess;
    }
}
