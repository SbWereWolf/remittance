<html>
<head>
    <meta charset="utf-8">
    <title>Обменник</title>
</head>

<body>

<dl id="transfer-list">
    <dd><label for="deal-income">Положить</label><input type="number" id="deal-income"></dd>
    <dd><label><input type="radio" name="transfer" id="transfer-sber" data-operaton-type="transfer"
                      data-currency-type="SBER">Sber</label></dd>
    <dd><label><input type="radio" name="transfer" id="transfer-alfa" data-operaton-type="transfer"
                      data-currency-type="ALFA">Alfa</label></dd>
</dl>

<dl id="receive-list">
    <dd><label for="deal-outcome">Получить</label>
        <output id="deal-outcome"></output>
    </dd>
    <dd><label><input type="radio" name="receive" id="receive-sber" data-operaton-type="receive"
                      data-currency-type="SBER">Sber</label></dd>
    <dd><label><input type="radio" name="receive" id="receive-alfa" data-operaton-type="receive"
                      data-currency-type="ALFA">Alfa</label></dd>
</dl>

<form onsubmit="return false;" method="post">
    <dl>
        <dt>Заполните информацию</dt>
        <dd><label for="deal-email">Email</label><input type="text" id="deal-email"></dd>
        <dd><label for="fio-transfer">ФИО отправителя</label><input type="text" id="fio-transfer"></dd>
        <dd><label for="account-transfer">Карта Сбера</label><input type="text" id="account-transfer"></dd>
        <dd><label for="fio-receive">ФИО получателя</label><input type="text" id="fio-receive"></dd>
        <dd><label for="account-receive">Карта Альфы</label><input type="text" id="account-receive"></dd>
        <dd><input type="submit" value="Обменять сейчас" onclick="doAddOrder();"></dd>
    </dl>

</form>
<div id="reception-validity"></div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
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
            url: '/order/add',
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
</script>
</html>
