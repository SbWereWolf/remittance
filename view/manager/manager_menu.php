<?php
/* @var $menu array */
use Remittance\Core\Common;
use Remittance\Web\ManagerPage;

$isSet = isset($menu);
$isValid = false;
if ($isSet) {
    $isValid = Common::isValidArray($menu);
}

if ($isValid):
    $empty = null;
    ?>
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
