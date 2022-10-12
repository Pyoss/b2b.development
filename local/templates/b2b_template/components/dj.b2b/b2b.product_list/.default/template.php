<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */

use Bitrix\Main\Page\Asset;
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/product_list.js");
?>

<div class="section">
    <h1 class="section__title">Оформить заказ</h1>
    <form class="product-filter b2b-block" id="product-filter">
        <h3 class="product-filter__title b2b-block__title">Поиск товара</h3>
        <div class="product-filter__list">
            <div class="filter-item">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "search",
                    array(
                        "COMPONENT_TEMPLATE" => "tree",
                        "IBLOCK_TYPE" => "catalog",
                        "IBLOCK_ID" => "2",
                        "SECTION_ID" => $_REQUEST["SECTION_ID"],
                        "SECTION_CODE" => "",
                        "COUNT_ELEMENTS" => "Y",
                        "TOP_DEPTH" => "3",
                        "SECTION_FIELDS" => array(
                            0 => "",
                            1 => "",
                        ),
                        "SECTION_USER_FIELDS" => array(
                            0 => "",
                            1 => "",
                        ),
                        "SECTION_URL" => "",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "36000000",
                        "CACHE_GROUPS" => "Y",
                        "ADD_SECTIONS_CHAIN" => "Y"
                    ),
                    $component
                );
                ?>
                <input id="section-input" name="sections" type="hidden">
            </div>
            <div class="filter-item">
                <label class="filter-item__label">Артикул, наименование</label>
                <input id="search-input" class="filter-item__input" name="search" type="search"
                       placeholder="Поиск товара">
            </div>
        </div>
    </form>
    <div class="product-table__wrapper b2b-block">
        <table class="product-table">
            <h3 class="product-table__title">Название категории</h3>
            <thead>
            <tr class="product-table__row--header">
                <?
                foreach ($arResult['GRID']['HEAD'] as $CLASS => $HEADER) {
                    ?>
                    <td class="product-table__cell <?= $CLASS ?>"><?= $HEADER ?></td>
                    <?
                } ?>
            </tr>
        </table>
        <div class="product-table__loading" id="loading" style="display: none">Загрузка ...</div>
    </div>
</div>
<script>
    product_list.display_properties = <?= \CUtil::PhpToJSObject($arParams['PROPERTY_ID'])?>
</script>