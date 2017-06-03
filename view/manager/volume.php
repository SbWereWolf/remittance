<?php
/* @var $volumes array */
/* @var $currencies array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */
/* @var $currencyTitles array */
/* @var $menu array */

use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\VolumeRecord;
use Remittance\Web\ManagerPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Объёмы валют</title>

    <style>
        table.rates-list {
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
    $isValid = Common::isValidArray($menu);
}

if ($isValid)
    include('manager_menu.php');
?>

<form id="add-volume" onsubmit="return false;">
    <dl>
        <dt>Добавить Объём</dt>
        <dd><label for="currency">Валюта</label>
            <?php
            $isValid = Common::isValidArray($currencies);
            if ($isValid) :?>
                <select id="currency" name="currency">
                    <?php
                    foreach ($currencies as $currencyCandidate):

                        $isObject = $currencyCandidate instanceof CurrencyRecord;
                        if ($isObject):
                            $currency = CurrencyRecord::adopt($currencyCandidate);
                            ?>
                            <option value="<?= $currency->code ?>"><?= $currency->title ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            <?php endif ?>
        </dd>
        <dd><label for="volume">Объём</label><input type="number" step="0.0001" id="volume" name="volume"></dd>
        <dd><label for="reserve">Резерв</label><input type="number" step="0.0001" id="reserve" name="reserve"></dd>
        <dd><label for="account-name">ФИО</label><input type="text" id="account-name" name="account_name"></dd>
        <dd><label for="account-number">Номер счёта</label><input type="text" id="account-number" name="account_number">
        </dd>
        <dd><label for="limitation">Лимит</label><input type="number" step="0.0001" id="limitation" name="limitation">
        </dd>
        <dd><label for="total">Использовано лимита</label><input type="number" step="0.0001" id="total" name="total">
        </dd>
        <dd><label for="disable">Флаг объём отключен</label><input type="checkbox" id="disable" name="disable"></dd>
        </dd>
        <dd><input type="submit" value="Добавить" onclick="doAddVolume();"></dd>
    </dl>

</form>
<div id="execution-result"></div>

<div id="message"></div>
<div id="pager" data-offset="<?= $offset ?>" data-limit="<?= $limit ?>"></div>
<div id="rates">
    <?php

    $isSet = isset($volumes);
    $isValid = false;
    if ($isSet) {
        $isValid = Common::isValidArray($volumes);
    }
    if ($isValid) :?>
        <table id="rates-list" class="rates-list">
            <thead>
            <tr>
                <th cell>Валюта</th>
                <th cell>Объём</th>
                <th cell>Резерв</th>
                <th cell>ФИО</th>
                <th cell>Номер счёта</th>
                <th cell>Лимит</th>
                <th cell>Использовано</th>
                <th cell>Флаг объём отключен</th>
                <th cell>Включить</th>
                <th cell>Отключить</th>
                <th cell>Изменить</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="navigation-spacer" colspan="9">&nbsp;&nbsp;</td>
                <td><a id="next-page" href="#" onclick="moveNext();">NEXT</a></td>
            </tr>
            </tfoot>
            <?php foreach ($volumes as $volumeCandidate): ?>
                <?php
                $isObject = $volumeCandidate instanceof VolumeRecord;
                if ($isObject) :
                    $volume = VolumeRecord::adopt($volumeCandidate);
                    $id = $volume->id;

                    $titles = Common::setIfExists($id, $currencyTitles, ICommon::EMPTY_ARRAY);
                    $isExists = !empty($titles);
                    $currencyTitle = ICommon::EMPTY_VALUE;
                    if ($isExists) {
                        $currencyTitle = Common::setIfExists(ManagerPage::CURRENCY_TITLE,
                            $titles,
                            ICommon::EMPTY_VALUE);
                    }
                    ?>
                    <tr>
                        <td cell><?= $currencyTitle ?></td>
                        <td cell><?= $volume->amount ?></td>
                        <td cell><?= $volume->reserve ?></td>
                        <td cell><?= $volume->accountName ?></td>
                        <td cell><?= $volume->accountNumber ?></td>
                        <td cell><?= $volume->limitation ?></td>
                        <td cell><?= $volume->total ?></td>
                        <td cell><input id="disable-<?= $volume->id ?>"
                                        type="checkbox" <?= $volume->isHidden ? 'checked' : '' ?>
                                        disabled>
                        </td>
                        <?php
                        $isValid = Common::isValidArray($actionLinks);

                        if (!$isValid):
                            ?>
                            <td cell colspan="3">&nbsp;&nbsp;</td>
                        <?php endif; ?>
                        <?php
                        $isExists = false;
                        $idCollection = ICommon::EMPTY_ARRAY;
                        if ($isValid) {

                            $idCollection = Common::setIfExists($id, $actionLinks, ICommon::EMPTY_ARRAY);
                            $isExists = !empty($idCollection);
                        }

                        if ($isExists):
                            ?>
                            <td cell>
                                <?php
                                $isExist = array_key_exists(ManagerPage::ACTION_VOLUME_ENABLE, $idCollection);
                                if ($isExist):
                                    ?><a class="action" href="javascript:return false;"
                                         data-action="<?= $idCollection[ManagerPage::ACTION_VOLUME_ENABLE] ?>">
                                        Включить</a>
                                <?php endif; ?>
                                <?php if (!$isExist): ?>
                                    &nbsp;&nbsp;
                                <?php endif; ?>
                            </td>
                            <td cell>
                                <?php
                                $isExist = array_key_exists(ManagerPage::ACTION_VOLUME_DISABLE, $idCollection);
                                if ($isExist):
                                    ?><a class="action" href="javascript:return false;"
                                         data-action="<?= $idCollection[ManagerPage::ACTION_VOLUME_DISABLE] ?>">
                                        Отключить</a>
                                <?php endif; ?>
                                <?php if (!$isExist): ?>
                                    &nbsp;&nbsp;
                                <?php endif; ?>
                            </td>
                            <td cell>
                                <?php
                                $isExist = array_key_exists(ManagerPage::ACTION_VOLUME_EDIT, $idCollection);
                                if ($isExist):
                                    ?><a class="action" href="javascript:return false;"
                                         data-action="<?= $idCollection[ManagerPage::ACTION_VOLUME_EDIT] ?>">
                                        Изменить</a>
                                <?php endif; ?>
                                <?php if (!$isExist): ?>
                                    &nbsp;&nbsp;
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>

                    </tr>
                <?php endif ?>
            <?php endforeach; ?>
        </table>
    <?php endif ?>
</div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function doAddVolume() {

        var form_data = $("#add-volume").serialize();

        $.ajax({
            type: 'POST',
            <?php
            $isExist = array_key_exists(ManagerPage::ACTION_VOLUME_ADD, $actionLinks);

            $link = '';
            if ($isExist) {
                $link = $actionLinks[ManagerPage::ACTION_VOLUME_ADD];
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
