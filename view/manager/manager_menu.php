<?php
/* @var $menu ManagerMenu
 */

use Remittance\Presentation\Web\Page\ManagerMenu;

$isSet = isset($menu);
$isValid = false;
if ($isSet) {
    $isValid = $menu instanceof ManagerMenu;
}

if ($isValid):
    ?>
    <div id="links">
        <dl>
            <dt>Справочники</dt>
            <?php
            $currencyLink=$menu->currencyLink;
            $isExists = !empty($currencyLink);
            if ($isExists):
                ?>
                <dd><a href="<?= $currencyLink ?>">Валюты</a></dd>
            <?php endif; ?>
            <?php
            $rateLink = $menu->rateLink;
            $isExists = !empty($rateLink);
            if ($isExists):
                ?>
                <dd><a href="<?= $rateLink ?>">Ставки</a></dd>
            <?php endif; ?>
            <?php
            $volumeLink=$menu->volumeLink;
            $isExists = !empty($volumeLink);
            if ($isExists):
                ?>
                <dd><a href="<?= $volumeLink ?>">Объёмы</a></dd>
            <?php endif; ?>
            <?php
            $feeLink = $menu->feeLink;
            $isExists = !empty($feeLink);
            if ($isExists):
                ?>
                <dd><a href="<?= $feeLink ?>">Комиссии</a></dd>
            <?php endif; ?>
        </dl>
        <dl>
            <dt>Настрокий</dt>
            <?php
            $settingLink = $menu->settingLink;
            $isExists = !empty($settingLink);
            if ($isExists):
                ?>
                <dd><a href="<?= $settingLink ?>">Общие</a></dd>
            <?php endif; ?>
        </dl>

    </div>
<?php endif; ?>
