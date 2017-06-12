<?php
/* @var $volume Remittance\DataAccess\Entity\VolumeRecord */
/* @var $currencies array */
/* @var $menu array */
/* @var $actionLinks array */

use Remittance\Core\Common;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\Presentation\Web\ManagerPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Объём валюты</title>
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

<h1>Объём валюты</h1>

<form id="save-volume" onsubmit="return false;">
    <dl>
        <dt>Изменить и сохранить Объём</dt>
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
                            <option value="<?= $currency->code ?>"
                                <?= ($currency->id == $volume->currencyId) ? 'selected' : 'disabled' ?>
                            ><?= $currency->title ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            <?php endif ?>
        </dd>
        <dd><label for="volume">Объём</label><input type="number" step="0.0001" id="volume" name="amount"
                                                    value="<?= $volume->amount ?>"></dd>
        <dd><label for="reserve">Резерв</label><input type="number" step="0.0001" id="reserve" name="reserve"
                                                      value="<?= $volume->reserve ?>"></dd>
        <dd><label for="account-number">Номер счёта</label><input type="text" id="account-number" name="account_number"
                                                                  value="<?= $volume->accountNumber ?>">
        <dd><label for="account-name">ФИО</label><input type="text" id="account-name" name="account_name"
                                                        value="<?= $volume->accountName ?>"></dd>
        <dd><label for="limitation">Лимит</label><input type="number" step="0.0001" id="limitation" name="limitation"
                                                        value="<?= $volume->limitation ?>">
        </dd>
        <dd><label for="total">Использовано лимита</label><input type="number" step="0.0001" id="total" name="total"
                                                                 value="<?= $volume->total ?>">
        </dd>
        <dd><label for="disable">Флаг объём отключен</label><input type="checkbox" id="disable"
                                                                   name="disable" <?= $volume->isHidden ? 'checked' : '' ?>>
        </dd>
        <dd><input type="submit" value="Сохранить" onclick="doSaveVolume();"></dd>
    </dl>

</form>
<div id="execution-result"></div>

<div id="message"></div>


</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function doSaveVolume() {

        var form_data = $("#save-volume").serialize();

        $.ajax({
            type: 'POST',
            <?php
            $isExist = array_key_exists(ManagerPage::ACTION_VOLUME_SAVE, $actionLinks);

            $link = '';
            if ($isExist) {
                $link = $actionLinks[ManagerPage::ACTION_VOLUME_SAVE];
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
