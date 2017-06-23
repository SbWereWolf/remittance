<?php
/* @var $transferView array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */
/* @var $menu OperatorMenu */

use Remittance\Presentation\Web\Page\OperatorMenu;

use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\Presentation\UserOutput\PlainText;
use Remittance\Presentation\Web\OperatorPage;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Список переводов</title>

    <style>
        table.transfers-list {
            border: 1px solid black;
            border-collapse: collapse;
        }

        [cell] {
            border: 1px solid black;
            border-collapse: collapse;
        }

    </style>
</head>

<body>

<?php
$isSet = isset($menu);
$isValid = false;
if($isSet){
    $isValid = $menu instanceof OperatorMenu;
}

if ($isValid)
    include('operator_menu.php');
?>

<h1>Список переводов</h1>

<div id="message"></div>
<div id="transfers-pager" data-offset="<?= $offset ?>" data-limit="<?= $limit ?>"></div>
<div id="transfers">
    <?php
    $isSet = isset($transferView);

    $isValid = false;
    if ($isSet) {
        $isValid = Common::isValidArray($transferView);
    }

    if ($isValid) :?>
        <table id="transfers-list" class="transfers-list">
            <thead>
            <tr>
                <th cell>Номер заявки</th>
                <th cell>Дата заявки</th>
                <th cell>Статус заявки</th>
                <th cell>Почта</th>
                <th cell>Валюта поступления</th>
                <th cell>Сумма Положить</th>
                <th cell>Счёт отправителя</th>
                <th cell>ФИО отправителя</th>
                <th cell>Счёт поступления</th>
                <th cell>ФИО поступления</th>
                <th cell>Время статуса</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="transfers-pages" colspan="9">&nbsp;&nbsp;</td>
                <td><a id="next-page" href="#" onclick="moveNext();">NEXT</a></td>
            </tr>
            </tfoot>
            <?php foreach ($transferView as $viewRow): ?>
                <?php

                $isValid = Common::isValidArray($viewRow);
                if ($isValid) :

                    $id = Common::setIfExists(OperatorPage::ID, $viewRow, ICommon::EMPTY_VALUE);
                    $empty = ICommon::EMPTY_VALUE;

                    $text = new PlainText();

                    $documentNumber = $text->printElement($viewRow, OperatorPage::DOCUMENT_NUMBER);
                    ?>
                    <tr>
                        <td cell>

                            <?php
                            $isValid = Common::isValidArray($actionLinks);
                            $idCollection = ICommon::EMPTY_ARRAY;
                            if ($isValid) {
                                $idCollection = Common::setIfExists($id, $actionLinks, ICommon::EMPTY_ARRAY);
                            }

                            $isValid = Common::isValidArray($idCollection);
                            $isExist = false;
                            if ($isValid) {
                                $isExist = array_key_exists(OperatorPage::ACTION_TRANSFER_EDIT, $idCollection);
                            }

                            if ($isExist):

                                $editLink = $idCollection[OperatorPage::ACTION_TRANSFER_EDIT];
                                ?>
                                <a href="<?= $editLink ?>"><?= $documentNumber ?></a>
                            <?php endif; ?>
                            <?php if (!$isExist): ?>
                                <?= $documentNumber ?>
                            <?php endif; ?>
                        </td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::DOCUMENT_DATE) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::TRANSFER_STATUS) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::DEAL_EMAIL) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::INCOME_CURRENCY) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::DEAL_INCOME) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::ACCOUNT_TRANSFER) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::NAME_TRANSFER) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::AWAIT_ACCOUNT) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::AWAIT_NAME) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::STATUS_TIME) ?></td>
                    </tr>
                <?php endif ?>
            <?php endforeach; ?>
        </table>
    <?php endif ?>
</div>
</body>

</html>
