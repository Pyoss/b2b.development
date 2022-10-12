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
use Bitrix1C\Api;
use DJ\B2B\Applications\B2BMain;


if ($this->StartResultCache(false, $USER -> GetID())){

    $api1C = new Api();
    $rsUsers = CUser::GetByID($USER -> GetID());
    while ($arUser = $rsUsers->Fetch()) {
        $arResult['DATA'] = $arUser;
    }
    $arResult['NAME'] =$arResult['DATA']['NAME'];
    $arResult['LAST_NAME'] =$arResult['DATA']['LAST_NAME'];
    $arResult['COMPANY_DATA'] = json_decode($api1C -> GetCompany(), true);
    $this -> includeComponentTemplate();
}
