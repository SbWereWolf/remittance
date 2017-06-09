<?php
/* @var $menu array */
use Remittance\Core\Common;
use Remittance\Presentation\Web\ManagerPage;

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
        $menuReference = Common::setIfExists(ManagerPage::REFERENCES_LINKS, $menu, $empty);
        $isExists = !empty($menuReference);
        if ($isExists):
            ?>
            <dl>
                <dt>Справочники</dt>
                <?php
                $menuCurrency = Common::setIfExists(ManagerPage::CURRENCY_REFERENCE, $menuReference, $empty);
                $isExists = !empty($menuCurrency);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $menuCurrency ?>">Валюты</a></dd>
                <?php endif; ?>
                <?php
                $menuRate = Common::setIfExists(ManagerPage::RATES_REFERENCE, $menuReference, $empty);
                $isExists = !empty($menuRate);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $menuRate ?>">Ставки</a></dd>
                <?php endif; ?>
                <?php
                $menuVolume = Common::setIfExists(ManagerPage::VOLUME_REFERENCE, $menuReference, $empty);
                $isExists = !empty($menuVolume);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $menuVolume ?>">Объёмы</a></dd>
                <?php endif; ?>
                <?php
                $menuFee = Common::setIfExists(ManagerPage::FEE_REFERENCE, $menuReference, $empty);
                $isExists = !empty($menuFee);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $menuFee ?>">Комиссии</a></dd>
                <?php endif; ?>
            </dl>
        <?php endif; ?>
        <?php
        $menuSettings = Common::setIfExists(ManagerPage::SETTINGS_LINKS, $menu, $empty);
        $isExists = !empty($menuSettings);
        if ($isExists):
            ?>
            <dl>
                <dt>Настрокий</dt>
                <?php
                $menuCommon = Common::setIfExists(ManagerPage::SETTINGS_COMMON, $menuSettings, $empty);
                $isExists = !empty($menuCommon);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $menuCommon ?>">Общие</a></dd>
                <?php endif; ?>
            </dl>
        <?php endif; ?>

    </div>
<?php endif; ?>
