<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/product_list.js");
?>

    <h1 class="section__title">Заказ № <?=$arParams['ORDER_ID']?> (<?=$arParams['ORDER_DATA']['status']?>)</h1>
    <div class="product-table__wrapper b2b-block">
        <table class="product-table"><colgroup id="colgroup_fixed">
                <col style="width: 105px;" class="article">
                <col style="width: 140px;" class="picture">
                <col style="width: 415px;" class="name">
                <col style="width: 85px;" class="price">
                <col style="width: 85px;" class="retail_price">
                <col style="width: 165px;" class="quantity">
                <col style="width: 105px;" class="sum">
            </colgroup>
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
            <span class="sum-value" id="order-sum"><?=$arParams['ORDER_DATA']['sum']?>&nbsp₽</span>
        </div>
    </div>
<script>
    product_list.page = 'order'
    product_list.order_id = <?=$arParams['ORDER_ID']?>
</script>