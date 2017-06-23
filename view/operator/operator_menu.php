<?php
/* @var $menu OperatorMenu */

use Remittance\Presentation\Web\Page\OperatorMenu;

$isSet = isset($menu);
$isValid = false;
if ($isSet) {
    $isValid = $menu instanceof OperatorMenu;
}

if ($isValid):
    ?>
    <div id="links">
        <dl>
            <dt>Документы</dt>
            <?php
            $transferLink = $menu->transferLink;
            $isExists = !empty($transferLink);
            if ($isExists):
                ?>
                <dd><a href="<?= $transferLink ?>">Переводы</a></dd>
            <?php endif; ?>
        </dl>
    </div>
<?php endif; ?>
