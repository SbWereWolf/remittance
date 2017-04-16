<?php
/* @var $rates array */
/* @var $currencies array */
/* @var $offset int */
/* @var $limit int */
/* @var $actionLinks array */
/* @var $currencyTitles array */

use Remittance\Core\Common;
use Remittance\Core\ICommon;
use Remittance\DataAccess\Entity\CurrencyRecord;
use Remittance\DataAccess\Entity\RateRecord;
use Remittance\Web\ManagerPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Ставки обмена</title>

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

<form id="add-rate" onsubmit="return false;">
    <dl>
        <dt>Добавить Ставку</dt>
        <dd><label for="source-currency">Валюта Положить</label>
            <?php
            $isValid = Common::isValidArray($currencies);
            if ($isValid) :?>
                <select id="source-currency" name="source_currency">
                    <?php
                    foreach ($currencies as $currencyCandidate):
                        $currency = CurrencyRecord::adopt($currencyCandidate);
                        ?>
                        <option value="<?= $currency->code ?>"><?= $currency->title ?></option>
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
                        <option value="<?= $currency->code ?>"><?= $currency->title ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif ?>
        </dd>
        <dd><label for="rate">Ставка</label><input type="number" step="0.0001" id="rate" name="rate"></dd>
        <dd><label for="fee">Коммиссия</label><input type="number" step="0.0001" id="fee" name="fee"></dd>
        <dd><label for="disable">Флаг ставка отключена</label><input type="checkbox" id="disable" name="disable"></dd>
        <dd><label for="default">Флаг ставка по умолчанию</label><input type="checkbox" id="default" name="default">
        </dd>
        <dd><input type="submit" value="Добавить" onclick="doAddRate();"></dd>
    </dl>

</form>
<div id="execution-result"></div>

<div id="message"></div>
<div id="pager" data-offset="<?= $offset ?>" data-limit="<?= $limit ?>"></div>
<div id="rates">
    <?php
    $isSet = isset($rates);
    $isArray = false;
    $isContain = false;
    if ($isSet) {
        $isArray = is_array($rates);
        $isContain = count($rates) > 0;
    }
    $isValid = $isArray && $isContain;
    if ($isValid) :?>
        <table id="rates-list" class="rates-list">
            <thead>
            <tr>
                <th cell>Валюта Положить</th>
                <th cell>Валюта Получить</th>
                <th cell>Ставка</th>
                <th cell>Коммиссия</th>
                <th cell>Действующая ставка</th>
                <th cell>флаг Ставка отключена</th>
                <th cell>флаг Обмен по умолчанию</th>
                <th cell>По умолчанию</th>
                <th cell>Включить</th>
                <th cell>Отключить</th>
                <th cell>Сохранить</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td><a id="previous-page" href="#" onclick="movePrevious();">PREVIOUS</a></td>
                <td id="navigation-spacer" colspan="9">&nbsp;&nbsp;</td>
                <td><a id="next-page" href="#" onclick="moveNext();">NEXT</a></td>
            </tr>
            </tfoot>
            <?php foreach ($rates as $rateCandidate): ?>
                <?php
                $isObject = $rateCandidate instanceof RateRecord;
                if ($isObject) :
                    $rate = RateRecord::adopt($rateCandidate);
                    $id = $rate->id;

                    $titles = Common::setIfExists($id, $currencyTitles, ICommon::EMPTY_ARRAY);
                    $isExists = !empty($titles);
                    $source = ICommon::EMPTY_VALUE;
                    $target = ICommon::EMPTY_VALUE;
                    if ($isExists) {
                        $source = Common::setIfExists(ManagerPage::RATE_SOURCE_CURRENCY_TITLE,
                            $titles,
                            ICommon::EMPTY_VALUE);
                        $target = Common::setIfExists(ManagerPage::RATE_TARGET_CURRENCY_TITLE,
                            $titles,
                            ICommon::EMPTY_VALUE);
                    }
                    ?>
                    <tr>
                        <td cell><?= $source ?></td>
                        <td cell><?= $target ?></td>
                        <td cell><?= $rate->exchangeRate ?></td>
                        <td cell><?= $rate->fee ?></td>
                        <td cell><?= $rate->effectiveRate ?></td>
                        <td cell><input id="disable-<?= $rate->id ?>"
                                        type="checkbox" <?= $rate->isDefault ? 'checked' : '' ?>
                                        disabled>
                        </td>
                        <td cell><input id="default-<?= $rate->id ?>"
                                        type="checkbox" <?= $rate->isHidden ? 'checked' : '' ?>
                                        disabled>
                        </td>
                        <td cell><a class="action" href="javascript:return false;"
                                    data-action="<?= $actionLinks[$id][ManagerPage::ACTION_RATE_DEFAULT] ?>">По
                                умолчанию</a>
                        </td>
                        <td cell><a class="action" href="javascript:return false;"
                                    data-action="<?= $actionLinks[$id][ManagerPage::ACTION_RATE_ENABLE] ?>">Включить</a>
                        </td>
                        <td cell><a class="action" href="javascript:return false;"
                                    data-action="<?= $actionLinks[$id][ManagerPage::ACTION_RATE_DISABLE] ?>">Отключить</a>
                        </td>
                        <td cell><a class="action" href="javascript:return false;"
                                    data-action="<?= $actionLinks[$id][ManagerPage::ACTION_RATE_SAVE] ?>">Сохранить</a>
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
    function doAddRate() {

        var form_data = $("#add-rate").serialize();

        $.ajax({
            type: 'POST',
            url: '<?= ManagerPage::MODULE_RATE . ManagerPage::PATH_SYMBOL . ManagerPage::ACTION_RATE_ADD ?>',
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