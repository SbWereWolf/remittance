<?php
/* @var $currency \Remittance\BusinessLogic\Manager\Currency */
/* @var $actionLinks array */
/* @var $menu array */

use Remittance\Core\Common;
use Remittance\Presentation\Web\ManagerPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Валюты для обмена</title>
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

<h1>Валюта</h1>

<form onsubmit="return false;" id="save-currency">
    <dl>
        <dt>Добавить Валюту</dt>
        <dd><label for="code">Код</label><input type="text" id="code" name="code" value="<?= $currency->code ?>"></dd>
        <dd><label for="title">Название</label><input type="text" id="title" name="title"
                                                      value="<?= $currency->title ?>"></dd>
        <dd><label for="description">Описание</label><input type="text" id="description" name="description"
                                                            value="<?= $currency->description ?>"></dd>
        <dd><label for="disable">Флаг валюта отключена</label><input type="checkbox" id="disable" name="disable"
                                                                     <?= $currency->isDisable ? 'checked' : '' ?>></dd>
        <dd><input type="submit" value="Сохранить" onclick="doSaveCurrency();"></dd>
    </dl>

</form>
<div id="execution-result"></div>

<div id="message"></div>


</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function doSaveCurrency() {

        var form_data = $("#save-currency").serialize();

        $.ajax({
            type: 'POST',
            <?php
            $isExist = array_key_exists(ManagerPage::ACTION_CURRENCY_SAVE, $actionLinks);

            $link = '';
            if ($isExist) {
                $link = $actionLinks[ManagerPage::ACTION_CURRENCY_SAVE];
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
