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

use Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\Fuser,
    Bitrix\Sale\PaySystem,
    Bitrix1C\Api;

$api = new Api();
$order = Order::load($arParams['ORDER_ID']);
$orderProps = $order->getPropertyCollection();
$order_guid = $orderProps->getItemByOrderPropertyCode('order_guid')->getValue();

if ($order_guid) {
    $orderData = json_decode($api->GetOrder($order_guid)->getResponseBody(), true);
    $arOrder['1C_DATA'] = $orderData;
    $arOrder['1C_DATA']['sum'] = 0;
    foreach ($arOrder['1C_DATA']['bucket'] as $item) {
        $arOrder['1C_DATA']['sum'] += $item['price'] * $item['quantity'];
    }
    $arResult['ORDER'] = $arOrder;
}
$this->includeComponentTemplate();