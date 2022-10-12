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
use DJ\B2B\Bitrix1C\Api;
use DJ\B2B\Applications\B2BMain;

\Bitrix\Main\Loader::includeModule('dj.b2b');

if ($this->StartResultCache(false, $USER -> GetID())){

    $api1C = new Api();
    $arResult['MANAGER_ID'] = $api1C -> getClientManagerId();
    $this -> includeComponentTemplate();
}
