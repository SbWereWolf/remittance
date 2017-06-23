<?php
/* @var $rate \Remittance\BusinessLogic\Manager\Rate */
/* @var $currencies array */
/* @var $actionLinks array */
/* @var $currencyTitles array */
/* @var $menu ManagerMenu */

use Remittance\Presentation\Web\Page\ManagerMenu;

use Remittance\Core\Common;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\Presentation\Web\ManagerPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Ставки обмена</title>
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

<h1>Ставка обмена</h1>

<form id="save-rate" onsubmit="return false;">
    <dl>
        <dt>Изменить Ставку</dt>
        <dd><label for="source-currency">Валюта Положить</label>
            <?php
            $isValid = Common::isValidArray($currencies);
            if ($isValid) :?>
                <select id="source-currency" name="source_currency">
                    <?php
                    foreach ($currencies as $currencyCandidate):
                        $currency = CurrencyRecord::adopt($currencyCandidate);
                        ?>
                        <option value="<?= $currency->code ?>" <?= ($currency->code == $rate->sourceCurrency) ? 'selected' : 'disabled' ?>><?= $currency->title ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif ?>
        </dd>
        <dd><label for="target-currency">Валюта Получить</label>
            <?php
            $isValid = Common::isValidArray($currencies);
            if ($isValid) :?>
                <select id="target-currency" name="target_currency">
                    <?php
                    foreach ($currencies as $currencyCandidate):
                        $currency = CurrencyRecord::adopt($currencyCandidate);
                        ?>
                        <option value="<?= $currency->code ?>" <?= ($currency->code == $rate->targetCurrency) ? 'selected' : 'disabled' ?>><?= $currency->title ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif ?>
        </dd>
        <dd><label for="ratio">Ставка</label>
            <input type="number" step="0.0001" id="ratio" name="ratio"
                                                   value="<?= $rate->ratio ?>"></dd>
        <dd><label for="fee">Коммиссия</label>
            <input type="number" step="0.0001" id="fee" name="fee"
                                                     value="<?= $rate->fee ?>"></dd>
        <dd><label for="disable">Флаг ставка отключена</label>
            <input type="checkbox" id="disable" name="disable"
                <?= $rate->isDisable ? 'checked' : '' ?>>
        </dd>
        <dd><label for="default">Флаг ставка по умолчанию</label>
            <input type="checkbox" id="default" name="default"
                   disabled
                <?= $rate->isDefault ? 'checked' : '' ?>>
        </dd>

        <dd><label for="description">Примечание</label>
            <input type="text" id="description" name="description" value="<?= $rate->description ?>"></dd>
        <dd><input type="submit" value="Сохранить" onclick="doSaveRate();"></dd>
    </dl>

</form>
<div id="execution-result"></div>

<div id="message"></div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function doSaveRate() {

        var form_data = $("#save-rate").serialize();

        $.ajax({
            type: 'POST',
            <?php
            $isExist = array_key_exists(ManagerPage::ACTION_RATE_SAVE, $actionLinks);

            $link = '';
            if ($isExist) {
                $link = $actionLinks[ManagerPage::ACTION_RATE_SAVE];
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
</script>

</html>
