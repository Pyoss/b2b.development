<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 * @var $component
 * @var $APPLICATION
 */
?>

<div class="section">
    <?php
    $APPLICATION->IncludeComponent(
        "dj.b2b:b2b.product_list",
        "order_product_list",
        array(
            "COMPONENT_TEMPLATE" => "order_product_list",
            "PROPERTY_ID" => array(
                0 => "9",
            ), "ORDER_ID" => $arParams['ORDER_ID'],
        ),
        $component
    );
    ?>
    <?php
    /*
    DJMain::displayString($arResult);
    DJMain::displayString(\Bitrix\Main\Context::getCurrent()->getRequest());
    */ ?>
</div>