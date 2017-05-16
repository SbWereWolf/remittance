<?php
/* @var $currencies array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */
/* @var $menu array */

use Remittance\Core\Common;
use Remittance\DataAccess\Entity\CurrencyRecord;
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

<?php
$isSet = isset($menu);
$isValid = false;
if($isSet){
    $isValid = Common::isValidArray($menu);
}

if ($isValid)
    include('manager_menu.php');
?>

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
    $isValid = Common::isValidArray($currencies);
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
                <td><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="currencies-pages" colspan="4">&nbsp;&nbsp;</td>
                <td><a id="next-page" href="#" onclick="moveNext();">NEXT</a></td>
            </tr>
            </tfoot>
            <?php foreach ($currencies as $currency): ?>
                <?php
                $isObject = $currency instanceof CurrencyRecord;
                if ($isObject) :

                    $asNamed = CurrencyRecord::adopt($currency);
                    $id = $asNamed->id ?>
                    <tr>
                        <td cell><?= $asNamed->code ?></td>
                        <td cell><?= $asNamed->title ?></td>
                        <td cell><input id="disable-<?= $asNamed->code ?>"
                                        type="checkbox" <?= $asNamed->isHidden ? 'checked' : '' ?>
                                        disabled>
                        </td>
                        <td cell><?= $asNamed->description ?></td>
                        <td cell><a class="action" href="javascript:return false;"
                                    data-action="<?= $actionLinks[$id][ManagerPage::ACTION_CURRENCY_ENABLE] ?>">Включить</a>
                        </td>
                        <td cell><a class="action" href="javascript:return false;"
                                    data-action="<?= $actionLinks[$id][ManagerPage::ACTION_CURRENCY_DISABLE] ?>">Отключить</a>
                        </td>
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
        const title = $("input[id='title']").val();
        const description = $("input[id='description']").val();
        const checkbox_disable = $("input[id='disable']:checked");
        const disable = (typeof(checkbox_disable) === 'undefined');

        $.ajax({
            type: 'POST',
            url: '<?= $actionLinks[ManagerPage::ACTION_CURRENCY_ADD] ?>',
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
