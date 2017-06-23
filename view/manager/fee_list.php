<?php
/* @var $transferView array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */
/* @var $menu ManagerMenu */

use Remittance\Presentation\Web\Page\ManagerMenu;

use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\Presentation\UserOutput\PlainText;
use Remittance\Presentation\Web\ManagerPage;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Список выполненных переводов</title>

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
if ($isSet) {
    $isValid = $menu instanceof ManagerMenu;
}

if ($isValid)
    include('manager_menu.php');
?>

<h1>Список выполненных переводов</h1>

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
                <th cell>Валюта поступления</th>
                <th cell>Сумма Положить</th>
                <th cell>Счёт поступления</th>
                <th cell>ФИО поступления</th>
                <th cell>Комиссия</th>
                <th cell>Тело перевода</th>
                <th cell>Валюта списания</th>
                <th cell>Сумма Получить</th>
                <th cell>Счёт списания</th>
                <th cell>ФИО списания</th>
                <th cell>Стоимость списания</th>
                <th cell>Время статуса</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="transfers-pages" colspan="12">&nbsp;&nbsp;</td>
                <td><a id="next-page" href="#" onclick="moveNext();">NEXT</a></td>
            </tr>
            </tfoot>
            <?php foreach ($transferView as $viewRow): ?>
                <?php

                $isValid = Common::isValidArray($viewRow);
                if ($isValid) :

                    $id = Common::setIfExists(ManagerPage::ID, $viewRow, ICommon::EMPTY_VALUE);
                    $empty = ICommon::EMPTY_VALUE;
                    
                    $text = new PlainText();
                    ?>
                    <tr>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::DOCUMENT_NUMBER) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::DOCUMENT_DATE) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::INCOME_CURRENCY) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::DEAL_INCOME) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::AWAIT_ACCOUNT) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::AWAIT_NAME) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::FEE) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::BODY) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::OUTCOME_CURRENCY) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::DEAL_OUTCOME) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::PROCEED_ACCOUNT) ?></td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::PROCEED_NAME) ?></td>
                        <td cell>стоимость не определена</td>
                        <td cell><?= $text->printElement($viewRow, ManagerPage::STATUS_TIME) ?></td>
                    </tr>
                <?php endif ?>
            <?php endforeach; ?>
        </table>
    <?php endif ?>
</div>
</body>

</html>
