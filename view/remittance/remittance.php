<?php
/* @var $currencies array */
use Remittance\Web\CustomerApi;
use Remittance\Web\CustomerPage;
use Remittance\Web\IPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Обменник</title>
</head>

<body>
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
    <dl id="transfer-list">
        <dd><label for="deal-income">Положить</label><input type="number" step="0.0001" class="argument"
                                                            id="deal-income"></dd>
        <?php foreach ($currencies as $currency): ?>
            <?php
            $isObject = $currency instanceof \Remittance\DataAccess\Entity\CurrencyRecord;
            if ($isObject) :
                $code = $currency->code;
                ?>
                <dd>
                    <label><input type="radio" name="transfer" class="argument"
                                  data-currency-type="<?= $code ?>"><?= $currency->title ?></label>
                </dd>
            <?php endif ?>
        <?php endforeach; ?>
    </dl>
<?php endif ?>

<?php if ($isValid) : ?>
    <dl id="receive-list">
        <dd><label for="deal-outcome">Получить</label>
            <output id="deal-outcome"></output>
        </dd>
        <?php foreach ($currencies as $currency): ?>
            <?php
            $isObject = $currency instanceof \Remittance\DataAccess\Entity\CurrencyRecord;
            if ($isObject) :
                $code = $currency->code;
                ?>
                <dd>
                    <label><input type="radio" name="receive" class="argument"
                                  data-currency-type="<?= $code ?>"><?= $currency->title ?></label>
                </dd>
            <?php endif ?>
        <?php endforeach; ?>
    </dl>
<?php endif ?>

<form onsubmit="return false;">
    <dl>
        <dt>Заполните информацию</dt>
        <dd><label for="deal-email">Email</label><input type="text" id="deal-email"></dd>
        <dd><label for="fio-transfer">ФИО отправителя</label><input type="text" id="fio-transfer"></dd>
        <dd><label for="account-transfer">Номер карты отправителя</label><input type="text" id="account-transfer"></dd>
        <dd><label for="fio-receive">ФИО получателя</label><input type="text" id="fio-receive"></dd>
        <dd><label for="account-receive">Номер карты получателя</label><input type="text" id="account-receive"></dd>
        <dd><input type="submit" value="Обменять сейчас" class="enquire"></dd>
    </dl>

</form>
<div id="reception-validity"></div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
    function doAddOrder() {

        const deal_income = $("input[id='deal-income']").val();
        const deal_outcome = $("output[id='deal-outcome']").val();
        const deal_email = $("input[id='deal-email']").val();

        const deal_source = $("#transfer-list :input:radio:checked[name='transfer']").data('currency-type');
        const deal_target = $("#receive-list :input:radio:checked[name='receive']").data('currency-type');

        const fio_transfer = $("input[id='fio-transfer']").val();
        const account_transfer = $("input[id='account-transfer']").val();
        const fio_receive = $("input[id='fio-receive']").val();
        const account_receive = $("input[id='account-receive']").val();

        $.ajax({
            type: 'POST',
            url: '<?= IPage::ROOT
            . CustomerPage::MODULE_ORDER
            . IPage::PATH_SYMBOL
            . CustomerPage::ACTION_ORDER_ADD ?>',
            data: {
                deal_income: deal_income,
                deal_outcome: deal_outcome,
                deal_source: deal_source,
                deal_target: deal_target,
                deal_email: deal_email,
                fio_transfer: fio_transfer,
                account_transfer: account_transfer,
                fio_receive: fio_receive,
                account_receive: account_receive
            },
            dataType: 'json',
            success: function (result) {

                const value = result.result;
                $("#reception-validity").html(value);
            }
        });
    }
    function doCalculate() {

        const deal_income = $("input[id='deal-income']").val();
        const deal_source = $("#transfer-list :input:radio:checked[name='transfer']").data('currency-type');
        const deal_target = $("#receive-list :input:radio:checked[name='receive']").data('currency-type');

        $.ajax({
            type: 'POST',
            url: '<?= IPage::ROOT . CustomerApi::MODULE_COMPUTE ?>',
            data: {
                deal_income: deal_income,
                deal_source: deal_source,
                deal_target: deal_target
            },
            dataType: 'json',
            success: function (result) {

                const value = result.result;
                $("output[id='deal-outcome']").html(value);
            }
        });
    }
    $(document).ready(function () {

            $(".argument").change(
                doCalculate
            );
            $(".enquire").click(
                doAddOrder
            );
        }
    )


</script>
</html>
