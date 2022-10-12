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
use Bitrix1C\Api;

$api = new Api();
$arResult['PAYMENT_RESULT'] = json_decode($api -> GetPaymentList(), true);
$this -> includeComponentTemplate();

