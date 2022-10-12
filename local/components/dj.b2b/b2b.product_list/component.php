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
include_once $_SERVER['DOCUMENT_ROOT'] . $componentPath . '/B2BCatalog.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $componentPath . '/B2BDetails.php';
use \B2BAjax\B2BCatalog;
use \B2BAjax\B2BDetails;

$ajax = $_GET['AJAX'];
if ($ajax === 'catalog') {
    $b2bcat = new B2BCatalog();
    $b2bcat -> formCatalogJson();
}elseif ($ajax === 'details') {
    CModule::IncludeModule('iblock');
    $b2bcat = new B2BCatalog();
    $b2bcat -> formDetailsJson();
} else {
    $this->IncludeComponentTemplate();
}

