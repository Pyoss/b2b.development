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

CModule::IncludeModule('dj.b2b');

use DJ\B2B\Applications\ApplicationsTable;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$APPLICATION_ID = $arParams['APPLICATION_ID'];

$resApplications = ApplicationsTable::getById($APPLICATION_ID);
$arApplication = $resApplications -> fetch();
$arResult['APPLICATION_DATA'] = $arApplication;
if (!$arApplication){
        \Bitrix\Iblock\Component\Tools::process404(
            trim($arParams['MESSAGE_404']) ?: 'Элемент или раздел инфоблока не найден',
            true,
            $arParams['SET_STATUS_404'] === 'Y',
            $arParams['SHOW_404'] === 'Y',
            $arParams['FILE_404']
        );
        return;
} else {
    foreach ($arResult['APPLICATION_DATA'] as $row  => $value){
        if (Loc::getMessage($row) && $row!== 'STATUS'){
            $arResult['APPLICATION_GRID'][Loc::getMessage($row)] = $value;
        }
    }
    $this -> includeComponentTemplate();
}
?>

