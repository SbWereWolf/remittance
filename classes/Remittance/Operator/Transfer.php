<?php

namespace Remittance\Operator;

use Remittance\Customer\Order;
use Remittance\DataAccess\Entity\TransferRecord;
use Remittance\DataAccess\Entity\TransferStatusRecord;
use Remittance\DataAccess\Search\NamedEntitySearch;
use Remittance\DataAccess\Search\TransferSearch;
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
    public $dealEmail = '';
    public $fioTransfer = '';
    public $accountTransfer = '';
    public $fioReceive = '';
    public $accountReceive = '';

    public $documentNumber = '';
    public $documentDate = '';
    public $incomeCurrency = '';
    public $outcomeCurrency = '';
    public $statusCode = self::STATUS_RECEIVED;
    public $statusComment = '';
    public $statusTime = '';

    public function add(Order $orderDetail):bool
    {

        $record = new TransferRecord();

        $isSuccess = $record->addEntity();

        if ($isSuccess) {
            $record->isHidden = TransferRecord::DEFINE_AS_NOT_HIDDEN;

            $record->documentNumber = $record->id;
            $record->documentDate = date(self::FORMAT_DOCUMENT_DATE);

            $statusId = $this->getTransferStatusId();
            $record->transferStatusId = $statusId;

            $record->statusComment = 'принята заявка с сайта';
            $record->statusTime = date(self::FORMAT_STATUS_TIME);
            $record->reportEmail = $orderDetail->dealEmail;

            $record->incomeAccount = $orderDetail->dealSource;
            $record->incomeAmount = $orderDetail->dealIncome;
            $record->outcomeAccount = $orderDetail->dealTarget;
            $record->outcomeAmount = $orderDetail->dealOutcome;

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
        $this->fioTransfer = $record->transferName;
        $this->accountTransfer = $record->transferAccount;
        $this->fioReceive = $record->receiveName;
        $this->accountReceive = $record->receiveAccount;
        $this->incomeCurrency = $record->incomeAccount;
        $this->dealIncome = $record->incomeAmount;
        $this->outcomeCurrency = $record->outcomeAccount;
        $this->dealOutcome = $record->outcomeAmount;

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
        $record->incomeAccount = $this->incomeCurrency;
        $record->incomeAmount = $this->dealIncome;
        $record->outcomeAccount = $this->outcomeCurrency;
        $record->outcomeAmount = $this->dealOutcome;

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
