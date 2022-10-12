<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */
?>

<a href="/order/" class="basket-button">
    <div>Корзина</div>
    <div class="basket-button__icon"></div>
    <i id='header-quantity' class="basket_button__quantity"><?=$arResult['QUANTITY']?></i>
</a>