<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

