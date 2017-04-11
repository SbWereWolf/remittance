<?php
/* @var $currencies array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */

use Remittance\Web\ManagerPage;
?>
<html>
<head>
    <meta charset="utf-8">
    <title>Валюты для обмена</title>

    <style>
        table.currencies-list {
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

<form onsubmit="return false;" method="post">
    <dl>
        <dt>Добавить Валюту</dt>
        <dd><label for="code">Код</label><input type="text" id="code"></dd>
        <dd><label for="title">Название</label><input type="text" id="title"></dd>
        <dd><label for="description">Описание</label><input type="text" id="description"></dd>
        <dd><label for="disable">Флаг валюта отключена</label><input type="checkbox" id="disable"></dd>
        <dd><input type="submit" value="Добавить" onclick="doAddCurrency();"></dd>
    </dl>

</form>
<div id="execution-result"></div>

<div id="message"></div>
<div id="currencies-pager" data-offset="<?= $offset ?>" data-limit="<?= $limit ?>"></div>
<div id="currencies">
    <?php
    $isSet = isset($currencies);
    $isArray = false;
    $isContain = false;
    if ($isSet) {
        $isArray = is_array($currencies);
        $isContain = count($currencies) > 0;
    }
    $isValid = $isArray && $isContain;
    if ($isValid) :?>
        <table id="currencies-list" class="currencies-list">
            <thead>
            <tr>
                <th cell>Код</th>
                <th cell>Название</th>
                <th cell>Флаг валюта отключена</th>
                <th cell>Описание</th>
                <th cell>Включить</th>
                <th cell>Отключить</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td ><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="currencies-pages" colspan="4">&nbsp;&nbsp;</td>
                <td><a id="next-page" href="#" onclick="moveNext();">NEXT</a></td>
            </tr>
            </tfoot>
            <?php foreach ($currencies as $currency): ?>
                <?php
                $isObject = $currency instanceof \Remittance\DataAccess\Entity\CurrencyRecord;
                if ($isObject) :
                    $id = $currency->id ?>
                    <tr>
                        <td cell><?= $currency->code ?></td>
                        <td cell><?= $currency->title ?></td>
                        <td cell><?= $currency->isHidden ?></td>
                        <td cell><?= $currency->description ?></td>
                        <td cell><a class="action" href="javascript:return false;" data-action="<?= $actionLinks[$id][ManagerPage::ACTION_ENABLE] ?>">Включить</a></td>
                        <td cell><a class="action" href="javascript:return false;" data-action="<?= $actionLinks[$id][ManagerPage::ACTION_DISABLE] ?>">Отключить</a></td>
                    </tr>
                <?php endif ?>
            <?php endforeach; ?>
        </table>
    <?php endif ?>
</div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function doAddCurrency() {

        const code = $("input[id='code']").val();
        const title = $("output[id='title']").val();
        const description = $("input[id='description']").val();
        const disable = $("input[id='disable']").val();

        $.ajax({
            type: 'POST',
            url: '/manager/currency/add',
            data: {
                code: code,
                title: title,
                description: description,
                disable: disable
            },
            dataType: 'json',
            success: function (result) {

                const value = result.result;
                $("#execution-result").html(value);
            }
        });
    }

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
