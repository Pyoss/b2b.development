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
CModule::IncludeModule('dj.imgref');

use DJScripts\ImgRef;

$rsBanners = \Bitrix\Iblock\ElementPropertyTable::getList(['filter' => ['IBLOCK_PROPERTY_ID' => 82], 'select' => ['IBLOCK_ELEMENT_ID', 'VALUE']]);
while ($arBanner = $rsBanners->fetch()) {
    if (\Bitrix\Iblock\ElementTable::getById($arBanner['IBLOCK_ELEMENT_ID']) -> fetch()['ACTIVE'] == 'Y'){

        $arBanner['PICTURE_DATA'] = CFile::GetPath($arBanner['VALUE']);
        $arResult['BRANDS'][] = $arBanner;
    }
}
$this->includeComponentTemplate();