<?php
/* @var $currencies array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */
/* @var $menu ManagerMenu */

use Remittance\Presentation\Web\Page\ManagerMenu;

use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\Presentation\Web\ManagerPage;

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
if ($isSet) {
    $isValid = $menu instanceof ManagerMenu;
}

if ($isValid)
    include('manager_menu.php');
?>

<h1>Валюты для обмена</h1>

<form onsubmit="return false;" method="post" id="add-currency">
    <dl>
        <dt>Добавить Валюту</dt>
        <dd><label for="code">Код</label><input type="text" id="code" name="code"></dd>
        <dd><label for="title">Название</label><input type="text" id="title" name="title"></dd>
        <dd><label for="description">Описание</label><input type="text" id="description" name="description"></dd>
        <dd><label for="disable">Флаг валюта отключена</label><input type="checkbox" id="disable" name="disable"></dd>
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
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="currencies-pages" colspan="2">&nbsp;&nbsp;</td>
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
                                $isExist = array_key_exists(ManagerPage::ACTION_CURRENCY_EDIT, $idCollection);
                            }

                            if ($isExist):

                                $editLink = $idCollection[ManagerPage::ACTION_CURRENCY_EDIT];
                                ?>
                                <a href="<?= $editLink ?>"><?= $asNamed->code ?></a>
                            <?php endif; ?>

                            <?php if (!$isExist): ?>
                                <?= $asNamed->code ?>
                            <?php endif; ?>

                        </td>
                        <td cell><?= $asNamed->title ?></td>
                        <td cell><input id="disable-<?= $asNamed->code ?>"
                                        type="checkbox" <?= $asNamed->isHidden ? 'checked' : '' ?>
                                        disabled>
                        </td>
                        <td cell><?= $asNamed->description ?></td>
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

        var form_data = $("#add-currency").serialize();

        $.ajax({
            type: 'POST',
            <?php
            $isExist = array_key_exists(ManagerPage::ACTION_CURRENCY_ADD, $actionLinks);

            $link = '';
            if ($isExist) {
                $link = $actionLinks[ManagerPage::ACTION_CURRENCY_ADD];
            }
            ?>
            url: '<?= $link ?>',
            data: {
                form_data: form_data
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
