<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/product_list.js");
?>

    <h1 class="section__title">Корзина</h1>
    <div class="product-table__wrapper b2b-block">
        <table class="product-table">
            <thead>
            <tr class="product-table__row--header">
                <?
                foreach ($arResult['GRID']['HEAD'] as $CLASS => $HEADER) {
                    ?>
                    <td class="product-table__cell <?= $CLASS ?>"><?= $HEADER ?></td>
                    <?
                } ?>
            </tr>
            </thead>
        </table>
        <div class="product-table__loading" id="loading" style="display: none">Загрузка ...</div>
        <div class="product-table__sum">
            <span class="sum-title">Итого:</span>
            <span class="sum-value" id="order-sum"><?=$arResult['SUM']?>&nbsp₽</span>
        </div>
    </div>
<script>
    product_list.display_properties = <?= \CUtil::PhpToJSObject($arParams['PROPERTY_ID'])?>
    product_list.page = 'basket'
</script>