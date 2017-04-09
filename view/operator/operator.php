<?php
/* @var $transfers array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */

use \Remittance\Web\OperatorPage as OperatorPage;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Список переводов</title>

    <style>
        table.transfers-list {
            border: 1px solid black;
            border-collapse: collapse ;
        }
        [cell]{
            border: 1px solid black;
            border-collapse: collapse ;
        }

    </style>
</head>

<body>
<div id="message"></div>
<div id="transfers-pager" data-offset="<?= $offset ?>" data-limit="<?= $limit ?>"></div>
<div id="transfers">
    <?php
    $isSet = isset($transfers);
    $isArray = false;
    $isContain = false;
    if ($isSet) {
        $isArray = is_array($transfers);
        $isContain = count($transfers) > 0;
    }
    $isValid = $isArray && $isContain;
    if ($isValid) :?>
        <table id="transfers-list" class="transfers-list">
            <thead>
            <tr>
                <th cell>Номер заявки</th>
                <th cell>Дата заявки</th>
                <th cell>Статус заявки</th>
                <th cell>Почта</th>
                <th cell>ФИО отправителя</th>
                <th cell>Счёт отправителя</th>
                <th cell>Счёт поступления</th>
                <th cell>Сумма Положить</th>
                <th cell>ФИО получателя</th>
                <th cell>Счёт получателя</th>
                <th cell>Счёт списания</th>
                <th cell>Сумма Получить</th>
                <th cell>Комментарий статуса</th>
                <th cell>Время статуса</th>
                <th cell>Выполнить</th>
                <th cell>Отменить</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td ><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="transfers-pages" colspan="14">&nbsp;&nbsp;</td>
                <td><a id="next-page" href="#" onclick="moveNext();">NEXT</a></td>
            </tr>
            </tfoot>
            <?php foreach ($transfers as $transfer): ?>
                <?php
                $isObject = is_object($transfer);
                if ($isObject) :
                    $id = $transfer->id ?>
                    <tr>
                        <td cell><?= $transfer->documentNumber ?></td>
                        <td cell><?= $transfer->documentDate ?></td>
                        <td cell><?= $transfer->status ?></td>
                        <td cell><?= $transfer->reportEmail ?></td>
                        <td cell><?= $transfer->transferName ?></td>
                        <td cell><?= $transfer->transferAccount ?></td>
                        <td cell><?= $transfer->incomeAccount ?></td>
                        <td cell><?= $transfer->incomeAmount ?></td>
                        <td cell><?= $transfer->receiveName ?></td>
                        <td cell><?= $transfer->receiveAccount ?></td>
                        <td cell><?= $transfer->outcomeAccount ?></td>
                        <td cell><?= $transfer->outcomeAmount ?></td>
                        <td cell><?= $transfer->statusComment ?></td>
                        <td cell><?= $transfer->statusTime ?></td>
                        <td cell><a class="action" href="javascript:return false;" data-action="<?= $actionLinks[$id][OperatorPage::ACTION_ACCOMPLISH] ?>">Выполнить</a></td>
                        <td cell><a class="action" href="javascript:return false;" data-action="<?= $actionLinks[$id][OperatorPage::ACTION_ANNUL] ?>">Отменить</a></td>
                    </tr>
                <?php endif ?>
            <?php endforeach; ?>
        </table>
    <?php endif ?>
</div>

<table><thead><tr><th>1</th></tr></thead><tfoot><tr><td>2</td></tr></tfoot><tbody><tr><td>3</td></tr></tbody></table>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('.action').click(function() {

        const link = $(this).data('action');

        $.ajax({
            type: 'POST',
            url: link,
            data: {
            },
            dataType: 'json',
            success: function (result) {

                const value = result.message;
                $("#message").html(value);
            }
        });

    });

</script>
</html>
