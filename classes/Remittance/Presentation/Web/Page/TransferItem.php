<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2017-06-13
 * Time: 14:58
 */

namespace Remittance\Presentation\Web\Page;

class TransferItem
{
    public $documentNumber = '';
    public $documentDate = '';
    public $status = '';
    public $statusTime = '';
    public $statusComment = '';
    public $customerEmail = '';
    public $incomeCurrency = '';
    public $accountTransfer = '';
    public $nameTransfer = '';
    public $dealIncome = '';
    public $accountAwait = '';
    public $nameAwait = '';
    public $outcomeCurrency = '';
    public $accountProceed = '';
    public $nameProceed = '';
    public $dealOutcome = '';
    public $cost = '';
    public $accountReceive = '';
    public $nameReceive = '';

    public $annulLink = '';
    public $accomplishLink = '';
}
