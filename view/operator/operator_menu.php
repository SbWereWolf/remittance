<?php
/* @var $menu array */
use Remittance\Core\Common;
use Remittance\Presentation\Web\OperatorPage;

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
        $menuDocument = Common::setIfExists(OperatorPage::DOCUMENTS_LINKS, $menu, $empty);
        $isExists = !empty($menuDocument);
        if ($isExists):
            ?>
            <dl>
                <dt>Документы</dt>
                <?php
                $menuTransfer = Common::setIfExists(OperatorPage::TRANSFER_DOCUMENTS, $menuDocument, $empty);
                $isExists = !empty($menuTransfer);
                if ($isExists):
                    ?>
                    <dd><a href="<?= $menuTransfer ?>">Переводы</a></dd>
                <?php endif; ?>
            </dl>
        <?php endif; ?>
    </div>
<?php endif; ?>
