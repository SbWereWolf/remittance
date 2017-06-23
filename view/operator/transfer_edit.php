<?php

/* @var $transferView array */
/* @var $actionLinks array */
/* @var $menu OperatorMenu */

use Remittance\Presentation\Web\Page\OperatorMenu;

use Remittance\Core\Common;
use Remittance\Presentation\UserOutput\PlainText;
use Remittance\Presentation\Web\OperatorPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Заявка на перевод</title>

    <style>
        label  {
            font-style: normal;
            font-variant: small-caps;
            font-weight: normal;
            font-size:medium;
            line-height: normal;
        }

        [property-value] {
            font-style: normal;
            font-variant: normal;
            font-weight: bold;
            font-size:large;
            line-height: normal;
        }
        [property-key] {
            font-style: normal;
            font-variant: small-caps;
            font-weight: normal;
            font-size:medium;
            line-height: normal;
        }

    </style>
</head>

<body>

<?php
$isSet = isset($menu);
$isValid = false;
if($isSet){
    $isValid = $menu instanceof OperatorMenu;
}

if ($isValid)
    include('operator_menu.php');
?>

<h1>Заявка на перевод</h1>

<dl>
    <dt>Операции</dt>
    <?php
    $id = Common::setIfExists(OperatorPage::ID,$transferView);
    ?>
    <dd><a class="action" href="javascript:return false;"
           data-action="<?= $actionLinks[$id][OperatorPage::ACTION_TRANSFER_ANNUL] ?>">Отменить</a></dd>
    <dd><a class="action" href="javascript:return false;"
           data-action="<?= $actionLinks[$id][OperatorPage::ACTION_TRANSFER_ACCOMPLISH] ?>">Выполнить</a></dd>
</dl>
<dl>
    <dt>Заявка</dt>
    <?php
    $text = new PlainText();
    ?>
    <dd><label >Номер заявки</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::DOCUMENT_NUMBER); ?></span>
    </dd>
    <dd><label >Дата заявки</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::DOCUMENT_DATE); ?></span>
    </dd>
    <dd><label >Статус</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::TRANSFER_STATUS); ?></span>
    </dd>
    <dd><label >Электронка</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::DEAL_EMAIL); ?></span>
    </dd>
    <dd><label >Валюта Положить</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::INCOME_CURRENCY); ?></span>
    </dd>
    <dd><label >Счёт отправителя</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::ACCOUNT_TRANSFER); ?></span>
    </dd>
    <dd><label >ФИО отправителя</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::NAME_TRANSFER); ?></span>
    </dd>
    <dd><label >Сумма положить</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::DEAL_INCOME); ?></span>
    </dd>
    <dd><label >Имя поступления</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::AWAIT_NAME); ?></span>
    </dd>
    <dd><label >Счёт поступления</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::AWAIT_ACCOUNT); ?></span>
    </dd>
    <dd><label >Валюта Получить</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::OUTCOME_CURRENCY); ?></span>
    </dd>
    <dd><label >ФИО списания</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::PROCEED_NAME); ?></span>
    </dd>
    <dd><label >Счёт списания</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::PROCEED_ACCOUNT); ?></span>
    </dd>
    <dd><label >Сумма получить</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::DEAL_OUTCOME); ?></span>
    </dd>
    <dd><label >Счёт получателя</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::ACCOUNT_RECEIVE); ?></span>
    </dd>
    <dd><label >ФИО получателя</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::NAME_RECEIVE); ?></span>
    </dd>
    <dd><label >Комментарий статуса</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::STATUS_COMMENT); ?></span>
    </dd>
    <dd><label >Время статуса</label>
        <span property-value ><?= $text->printElement($transferView,OperatorPage::STATUS_TIME); ?></span>
    </dd>
</dl>
<div id="execution-result"></div>

<div id="message"></div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

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
