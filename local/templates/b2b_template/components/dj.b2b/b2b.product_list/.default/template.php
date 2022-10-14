<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var $arResult
 * @var $arParams
 */

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/product_list.js");
?>

<div class="section product-list">
    <div class="product-table__wrapper b2b-block">
        <form class="product-filter b2b-block" id="product-filter">
            <div class="product-filter__list">
                <div class="filter-item">
                    <label class="filter-item__label">Артикул, наименование</label>
                    <input id="search-input" class="filter-item__input" name="search" type="search" style="width:300px"
                           placeholder="Поиск товара">
                </div>
                <div class="filter-item">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "search",
                        array(
                            "COMPONENT_TEMPLATE" => "search",
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
                    ); ?>
                    <input id="section-input" name="sections" type="hidden" value="<?=$arResult['CURRENT_SECTION_ID'] ?: ''?>">
                </div>
                <div class="filter-item">
                    <label class="filter-item__label">Бренд</label>
                    <select class="filter-item__input" id="brands-input" name="BRAND" style="width:205px">
                        <?foreach ($arResult['BRANDS'] as $brand):?>
                            <option value="<?=$brand['ID']?>" <?=$brand['ID'] == $arResult['CURRENT_BRAND_ID'] ? 'selected' : ''?>><?=$brand['NAME']?></option>
                        <?endforeach;?>
                    </select>
                </div>
                <div class="filter-item">
                    <label class="filter-item__label">Акции, хит и новинки</label>
                    <select class="filter-item__input" id="sale-input" name="b2b_sale" style="width:205px">
                        <option value="0">Все</option>
                        <?foreach ($arResult['SALE'] as $sale):?>
                            <option value="<?=$sale['VALUE']?>" <?=$sale['VALUE'] == $arResult['CURRENT_SALE_NAME'] ? 'selected' : ''?>><?=$sale['VALUE']?></option>
                        <?endforeach;?>
                    </select>
                </div>
            </div>
        </form>
        <table class="product-table">
            <colgroup id="colgroup_fixed">
                <col style="width: 97px;" class="article">
                <col style="width: 134px;" class="picture">
                <col style="width: 303px;" class="name">
                <col style="width: 78px;" class="sale">
                <col style="width: 80px;" class="price">
                <col style="width: 80px;" class="retail_price">
                <col style="width: 80px;" class="margin">
                <col style="width: 146px;" class="quantity">
                <col style="width: 81px;" class="sum">
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
    </div>
</div>
<script>
    product_list.display_properties = <?= \CUtil::PhpToJSObject($arParams['PROPERTY_ID'])?>
</script>