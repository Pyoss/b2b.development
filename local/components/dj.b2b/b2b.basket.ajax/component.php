<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var B2BAjaxBasket $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

CModule::IncludeModule('dj.b2b');

$action = $_GET['action'];
if ($action == 'update'){
    $productId = $_GET['product_id'];
    $quantity = $_GET['quantity'];
    $this -> updateItem($productId, $quantity);
    $this -> commit();
    $this -> echoSelf();
} else if ($action == 'delete'){
    $productId = $_GET['product_id'];
    $this -> deleteItem($productId);
    $this -> commit();
    $this -> echoSelf();
} else if ($action == 'get'){
    $this -> echoSelf();
}
?>