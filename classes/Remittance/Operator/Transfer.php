<?php

namespace Remittance\Operator;

use Remittance\Customer\Order;
use Remittance\DataAccess\Entity\TransferRecord;
use Remittance\DataAccess\Entity\TransferStatusRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Remittance\DataAccess\Search\TransferSearch;
use Remittance\DataAccess\Search\VolumeSearch;
use Remittance\Exchange\Deal;
use Remittance\Manager\Volume;


class Transfer
{
    const STATUS_RECEIVED = 'RECEIVED';
    const STATUS_ACCOMPLISH = 'ACCOMPLISH';
    const STATUS_ANNUL = 'ANNUL';

    const ACTION_ACCOMPLISH = 'accomplish';

    const FORMAT_DOCUMENT_DATE = 'Ymd h:i:s';
    const FORMAT_STATUS_TIME = 'Ymd h:i:s';

    public $dealIncome = 0;
    public $dealOutcome = 0;
    public $fee = 0;
    public $body = 0;

    public $accountAwait = '';
    public $fioAwait = '';
    public $accountProceed = '';
    public $fioProceed = '';
    public $incomeCurrency = '';
    public $accountTransfer = '';
    public $fioTransfer = '';
    public $outcomeCurrency = '';
    public $accountReceive = '';
    public $fioReceive = '';

    public $documentNumber = '';
    public $documentDate = '';
    public $dealEmail = '';

    public $statusCode = self::STATUS_RECEIVED;
    public $statusComment = '';
    public $statusTime = '';

    public function add(Order $orderDetail):bool
    {
        $deal = new Deal($orderDetail->dealSource, $orderDetail->dealTarget, $orderDetail->dealIncome);
        $isSuccess = $deal->precomputation();

        $sourceVolume = new Volume();
        $targetVolume = new Volume();
        if ($isSuccess) {
            $volumeSearcher = new VolumeSearch();
            $sourceVolume = $volumeSearcher->searchByCurrency($orderDetail->dealSource);
            $targetVolume = $volumeSearcher->searchByCurrency($orderDetail->dealTarget);

            $isSuccess = !empty($sourceVolume->id) && !empty($targetVolume->id);
        }

        $statusId = null;
        if ($isSuccess) {
            $statusId = $this->getTransferStatusId();

            $isSuccess = !empty($statusId);
        }

        $record = new TransferRecord();
        if ($isSuccess) {
            $isSuccess = $record->addEntity();
        }

        if ($isSuccess) {

            $record->isHidden = TransferRecord::DEFINE_AS_NOT_HIDDEN;

            $record->documentNumber = $record->id;
            $record->documentDate = date(self::FORMAT_DOCUMENT_DATE);


            $record->transferStatusId = $statusId;

            $record->statusComment = 'принята заявка с сайта';
            $record->statusTime = date(self::FORMAT_STATUS_TIME);
            $record->reportEmail = $orderDetail->dealEmail;

            $record->incomeCurrency = $orderDetail->dealSource;
            $record->incomeAmount = $orderDetail->dealIncome;
            $record->outcomeCurrency = $orderDetail->dealTarget;

            $record->outcomeAmount = $deal->outcome;
            $record->fee = $deal->feeAmount;
            $record->body = $deal->body;

            $record->awaitAccount = $sourceVolume->accountNumber;
            $record->awaitName = $sourceVolume->accountName;
            $record->proceedAccount = $targetVolume->accountNumber;
            $record->proceedName = $targetVolume->accountName;

            $record->transferAccount = $orderDetail->accountTransfer;
            $record->transferName = $orderDetail->fioTransfer;
            $record->receiveName = $orderDetail->fioReceive;
            $record->receiveAccount = $orderDetail->accountReceive;

            $isSuccess = $record->mutateEntity();
        }

        if ($isSuccess) {

            $isSuccess = $this->assume($record);

        }

        return $isSuccess;
    }

    /** Принять значения свойств из записи базы данных
     * @param TransferRecord $record запись базы данных
     * @return bool успех выполнения операции
     */
    public function assume(TransferRecord $record): bool
    {

        $this->documentNumber = $record->documentNumber;
        $this->documentDate = $record->documentDate;

        $statusCode = $this->getTransferStatusCode($record);
        $this->statusCode = $statusCode;

        $this->statusComment = $record->statusComment;
        $this->statusTime = $record->statusTime;
        $this->dealEmail = $record->reportEmail;
        $this->fioAwait = $record->awaitName;
        $this->accountAwait = $record->awaitAccount;
        $this->fioTransfer = $record->transferName;
        $this->accountTransfer = $record->transferAccount;
        $this->fioReceive = $record->receiveName;
        $this->accountReceive = $record->receiveAccount;
        $this->incomeCurrency = $record->incomeCurrency;
        $this->dealIncome = $record->incomeAmount;
        $this->outcomeCurrency = $record->outcomeCurrency;
        $this->dealOutcome = $record->outcomeAmount;
        $this->fee = $record->fee;
        $this->body = $record->body;
        $this->accountProceed = $record->proceedAccount;
        $this->fioProceed = $record->proceedName;
        $this->accountAwait = $record->awaitAccount;
        $this->fioAwait = $record->awaitName;

        return true;
    }

    public function accomplish(): bool
    {

        $outcomeVolume = new Volume();
        $isSuccess = $outcomeVolume->applyOutcome($this->outcomeCurrency, $this->dealOutcome);

        $incomeVolume = new Volume();
        if ($isSuccess) {
            $isSuccess = $incomeVolume->applyIncome($this->incomeCurrency, $this->dealIncome);
        }

        $result = false;
        if ($isSuccess) {
            $this->statusCode = self::STATUS_ACCOMPLISH;
            $this->statusTime = date(self::FORMAT_STATUS_TIME);
            $this->statusComment = 'выполнено';
            $result = $this->save();
        }

        return $result;
    }

    public function annul(): bool
    {

        $this->statusCode = self::STATUS_ANNUL;
        $this->statusTime = date(self::FORMAT_STATUS_TIME);
        $this->statusComment = 'отменено';
        $result = $this->save();


        return $result;
    }

    private function save(): bool
    {

        $record = $this->assembleRecord();
        $result = $record->save();

        if ($result) {
            $this->assume($record);
        }

        return $result;
    }

    private function assembleRecord(): TransferRecord
    {

        $record = new TransferRecord();

        $record->documentNumber = $this->documentNumber;
        $record->documentDate = $this->documentDate;

        $statusId = $this->getTransferStatusId();
        $record->transferStatusId = $statusId;

        $record->statusComment = $this->statusComment;
        $record->statusTime = $this->statusTime;
        $record->reportEmail = $this->dealEmail;
        $record->transferName = $this->fioTransfer;
        $record->transferAccount = $this->accountTransfer;
        $record->receiveName = $this->fioReceive;
        $record->receiveAccount = $this->accountReceive;
        $record->incomeCurrency = $this->incomeCurrency;
        $record->incomeAmount = $this->dealIncome;
        $record->outcomeCurrency = $this->outcomeCurrency;
        $record->outcomeAmount = $this->dealOutcome;
        $record->fee = $this->fee;
        $record->body = $this->body;
        $record->proceedAccount = $this->accountProceed;
        $record->proceedName = $this->fioProceed;
        $record->awaitAccount = $this->accountAwait;
        $record->awaitName = $this->fioAwait;

        return $record;
    }

    /**
     * @param $id
     * @return bool
     */
    public function assembleTransfer($id): bool
    {
        $searcher = new TransferSearch();
        $transferRecord = $searcher->searchById($id);
        $result = $this->assume($transferRecord);

        return $result;
    }

    /**
     * @return string идентификатор записи статуса Заявки на перевод
     */
    private function getTransferStatusId(): string
    {
        $searcher = new NamedEntitySearch(TransferStatusRecord::TABLE_NAME);
        $transferStatus = $searcher->searchByCode($this->statusCode);
        $id = $transferStatus->id;

        return $id;
    }

    /**
     * @param TransferRecord $record запись Заявки на перевод
     * @return string код статуса Заявки наперевод
     */
    private function getTransferStatusCode(TransferRecord $record): string
    {
        $searcher = new NamedEntitySearch(TransferStatusRecord::TABLE_NAME);
        $transferStatus = $searcher->searchById($record->transferStatusId);
        $code = $transferStatus->code;

        return $code;
    }

    /** Получить имя для статуса Заявки на перевод
     * @return string Имя статуса Заявки на перевод
     */
    public function getTransferStatusTitle(): string
    {
        $searcher = new NamedEntitySearch(TransferStatusRecord::TABLE_NAME);
        $transferStatus = $searcher->searchByCode($this->statusCode);
        $title = $transferStatus->title;

        return $title;
    }
}
