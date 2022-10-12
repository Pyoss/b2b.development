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

$rsUsers = CUser::GetByID($USER -> GetID());
if ($_POST){
    $arFields['NAME'] = explode(' ', $_POST['profile-name'])[0];
    $arFields['LAST_NAME'] = explode(' ', $_POST['profile-name'])[1];
    $arFields['EMAIL'] = $_POST['profile-email'];
    $arFields['PHONE'] = $_POST['profile-phone'];
    $USER -> Update($USER -> GetID(), $arFields);
    $arResult = array_merge($arResult, $arFields);
} else {
    while ($arUser = $rsUsers->Fetch()) {
        $arResult['DATA'] = $arUser;
    }
    $arResult['NAME'] =$arResult['DATA']['NAME'];
    $arResult['LAST_NAME'] =$arResult['DATA']['LAST_NAME'];
    $arResult['EMAIL'] =$arResult['DATA']['EMAIL'];
    $arResult['PHONE'] =$arResult['DATA']['PERSONAL_PHONE'];

}
$this -> includeComponentTemplate();
