<?php
/* @var $currencies array */
/* @var $currenciesVolume array */
/* @var $actionLinks array */

use Remittance\Core\Common;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\Web\CustomerPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Обменник</title>
</head>

<body>

<h1>Обменник</h1>

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
        <?php foreach ($currencies as $currencyCandidate): ?>
            <?php
            $isObject = $currencyCandidate instanceof CurrencyRecord;
            if ($isObject) :
                $currency = CurrencyRecord::adopt($currencyCandidate);
                ?>
                <dd>
                    <label><input type="radio" name="transfer" class="argument"
                                  data-currency-type="<?= $currency->code ?>"><?= $currency->title ?>
                    </label>
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
        <dd><label for="deal-ratio">Обмен по курсу</label>
            <output id="deal-ratio"></output>
        </dd>
        <?php foreach ($currencies as $currencyCandidate): ?>
            <?php
            $isObject = $currencyCandidate instanceof CurrencyRecord;
            if ($isObject) :

                $currency = CurrencyRecord::adopt($currencyCandidate);

                $code = $currency->code;
                $id = $currency->id;

                $volume = Common::setIfExists($id, $currenciesVolume, Common::EMPTY_VALUE);
                ?>
                <dd>
                    <label><input type="radio" name="receive" class="argument"
                                  data-currency-type="<?= $code ?>"><?= $currency->title ?>
                        <?php
                        $isExists = !empty($volume);
                        if ($isExists): ?>
                            (<?= $volume ?>)
                        <?php endif; ?>
                    </label>
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
            url: '<?= $actionLinks[CustomerPage::ACTION_ORDER_ADD] ?>',
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
            url: '<?= $actionLinks[CustomerPage::ACTION_COMPUTE] ?>',
            data: {
                deal_income: deal_income,
                deal_source: deal_source,
                deal_target: deal_target
            },
            dataType: 'json',
            success: function (result) {

                const outcome = result.outcome;
                const income_currency = result.income_currency;
                const income_amount = result.income_amount;
                const outcome_currency = result.outcome_currency;
                const outcome_amount = result.outcome_amount;

                const ratio_text = income_amount
                    + ' ' + income_currency
                    + ' => ' + outcome_amount
                    + ' ' + outcome_currency;

                $("output[id='deal-outcome']").html(outcome);
                $("output[id='deal-ratio']").html(ratio_text);
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
