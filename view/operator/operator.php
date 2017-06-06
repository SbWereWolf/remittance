<?php
/* @var $transferView array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */

use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\UserOutput\PlainText;
use Remittance\Web\OperatorPage;

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
                <th cell>Валюта списания</th>
                <th cell>Сумма Получить</th>
                <th cell>Счёт списания</th>
                <th cell>ФИО списания</th>
                <th cell>Счёт получателя</th>
                <th cell>ФИО получателя</th>
                <th cell>Комментарий статуса</th>
                <th cell>Время статуса</th>
                <th cell>Выполнить</th>
                <th cell>Отменить</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="transfers-pages" colspan="18">&nbsp;&nbsp;</td>
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
                    ?>
                    <tr>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::DOCUMENT_NUMBER) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::DOCUMENT_DATE) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::TRANSFER_STATUS) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::DEAL_EMAIL) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::INCOME_CURRENCY) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::DEAL_INCOME) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::ACCOUNT_TRANSFER) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::FIO_TRANSFER) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::AWAIT_ACCOUNT) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::AWAIT_NAME) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::OUTCOME_CURRENCY) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::DEAL_OUTCOME) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::PROCEED_ACCOUNT) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::PROCEED_NAME) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::ACCOUNT_RECEIVE) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::FIO_RECEIVE) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::STATUS_COMMENT) ?></td>
                        <td cell><?= $text->printElement($viewRow, OperatorPage::STATUS_TIME) ?></td>
                        <td cell><a class="action" href="javascript:return false;"
                                    data-action="<?= $actionLinks[$id][OperatorPage::ACTION_ACCOMPLISH] ?>">Выполнить</a>
                        </td>
                        <td cell><a class="action" href="javascript:return false;"
                                    data-action="<?= $actionLinks[$id][OperatorPage::ACTION_ANNUL] ?>">Отменить</a></td>
                    </tr>
                <?php endif ?>
            <?php endforeach; ?>
        </table>
    <?php endif ?>
</div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.action').click(function () {

        const link = $(this).data('action');

        $.ajax({
            type: 'POST',
            url: link,
            data: {},
            dataType: 'json',
            success: function (result) {

                const value = result.message;
                $("#message").html(value);
            }
        });

    });

</script>
</html>
