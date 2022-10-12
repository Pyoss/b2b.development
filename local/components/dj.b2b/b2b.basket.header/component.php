<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');
$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(),
    Bitrix\Main\Context::getCurrent()->getSite());
$arResult['QUANTITY'] = array_sum($basket -> getQuantityList());
$this -> includeComponentTemplate();