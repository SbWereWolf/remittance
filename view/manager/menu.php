<?php
/* @var $menu array */
use Remittance\Core\Common;
use Remittance\Web\ManagerPage;

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Меню менеджера</title>
</head>

<body>
<?php
$isExists = isset($menu);
$isArray = false;
if ($isExists) {
    $isArray = is_array($menu);
}

if ($isArray):
    $empty = null;
    ?>
    <div id="navigation">
        <?php
        $navigation = Common::setIfExists(ManagerPage::NAVIGATION_MENU, $menu, $empty);
        $isExists = !empty($navigation);
        if ($isExists):
            ?>
            <dl>
                <dt>Страницы</dt>
                <?php
                $root = Common::setIfExists(ManagerPage::ROOT, $navigation, $empty);
                $isExists = !empty($root);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $root ?>">Меню</a></dd>
                <?php endif; ?>
            </dl>
        <?php endif; ?>
    </div>
    <div id="links">
        <?php
        $reference = Common::setIfExists(ManagerPage::REFERENCES_LINKS, $menu, $empty);
        $isExists = !empty($reference);
        if ($isExists):
            ?>
            <dl>
                <dt>Справочники</dt>
                <?php
                $currency = Common::setIfExists(ManagerPage::CURRENCY_REFERENCE, $reference, $empty);
                $isExists = !empty($currency);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $currency ?>">Валюты</a></dd>
                <?php endif; ?>
                <?php
                $rate = Common::setIfExists(ManagerPage::RATES_REFERENCE, $reference, $empty);
                $isExists = !empty($rate);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $rate ?>">Ставки</a></dd>
                <?php endif; ?>
                <?php
                $account = Common::setIfExists(ManagerPage::ACCOUNTS_REFERENCE, $reference, $empty);
                $isExists = !empty($account);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $account ?>">Счета</a></dd>
                <?php endif; ?>
            </dl>
        <?php endif; ?>
        <?php
        $settings = Common::setIfExists(ManagerPage::SETTINGS_LINKS, $menu, $empty);
        $isExists = !empty($settings);
        if ($isExists):
            ?>
            <dl>
                <dt>Настрокий</dt>
                <?php
                $common = Common::setIfExists(ManagerPage::SETTINGS_COMMON, $settings, $empty);
                $isExists = !empty($common);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $common ?>">Общие</a></dd>
                <?php endif; ?>
            </dl>
        <?php endif; ?>

    </div>
<?php endif; ?>

</body>

</html>
