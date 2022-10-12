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
    DJ\B2B\Bitrix1C\Api;

global $USER;

Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");
Bitrix\Main\Loader::includeModule("dj.b2b");

$siteId = Context::getCurrent()->getSite();
$currencyCode = CurrencyManager::getBaseCurrency();

// Создаёт новый заказ
$order = Order::create($siteId, $USER->isAuthorized() ? $USER->GetID() : 539);
$order->setPersonTypeId(3);
$order->setField('CURRENCY', $currencyCode);
$basket = Basket::loadItemsForFUser(Fuser::getId(), $siteId);
if ($basket -> isEmpty()){
    LocalRedirect('/order/empty.php');
}
$request = Context::getCurrent()->getRequest();
$method = $request->getServer()->getRequestMethod();
if ($method === 'GET') {
// Создаём одну отгрузку и устанавливаем способ доставки - "Без доставки" (он служебный)
    $shipmentCollection = $order->getShipmentCollection();
    $shipment = $shipmentCollection->createItem();
    $services = Delivery\Services\Manager::getRestrictedList($shipment, CLIENT_MODE);
    $arResult['DELIVERY_SERVICES'] = $services;
    $propertyCollection = $order->getPropertyCollection();
    foreach ($propertyCollection->toArray() as $prop) {
        $arProps = \Bitrix\Sale\Internals\OrderPropsTable::getList(
            array('filter' => array('ID' => $prop['ORDER_PROPS_ID']))
        )->fetch();
        if ($arProps['TYPE'] == 'ENUM') {
            $arProps['VALUES'] = \Bitrix\Sale\Internals\OrderPropsVariantTable::getList(array(
                'filter' => array('ORDER_PROPS_ID' => $arProps['ID'])))->fetchAll();
        }
        $arResult['PROPS'][] = $arProps;
    }

    $this->includeComponentTemplate();
} elseif ($method == 'POST') {
    $order->setBasket($basket);
    $deliverySystemId = $request->getPost('delivery');
    $packageString = $request->getPost('B2Bpackage');
    $comment = $request->getPost('B2Bcomment');
    $shipmentDate = $request->getPost('B2Bshipment_date');
    if ($comment) {
        $order->setField('USER_DESCRIPTION', $comment); // Устанавливаем поля комментария покупателя
    }
    $shipmentCollection = $order->getShipmentCollection();
    $shipment = $shipmentCollection->createItem();
    $service = Delivery\Services\Manager::getById($deliverySystemId);
    $shipment->setFields(array(
        'DELIVERY_ID' => $service['ID'],
        'DELIVERY_NAME' => $service['NAME'],
    ));

    if ($service['NAME'] == 'Другое (укажите в комментариях)'){
        $service['NAME'] = 'Указана в комментариях';
    }
    DJMain::displayString($service['NAME']);
    $paymentCollection = $order->getPaymentCollection();
    $payment = $paymentCollection->createItem();
    $paySystemService = PaySystem\Manager::getObjectById(1);
    $payment->setFields(array(
        'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
        'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
    ));

    $propertyCollection = $order->getPropertyCollection();
    foreach (['B2Bpackage', 'B2Bcomment', 'B2Bshipment_date'] as $propCode) {

        $prop = $propertyCollection->getItemByOrderPropertyCode($propCode);
        $prop->setValue($request->getPost($propCode));
    }
    $order->doFinalAction(true);
    $result = $order->save();
    $orderId = $order->getId();
    if ($orderId) {
        $arOrder = array();
        $itemCollection = $basket->getBasketItems();
        $api = new Api();
        foreach ($itemCollection as $item) {
            $basketArray[] = array(
                'guid' => $api->getProductGuid($item->getProductId())['UF_GUID'],
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice() * $item->getQuantity()
            );
        }
        $arOrder['bucket'] = $basketArray;
        $arOrder['city'] = '';
        $arOrder['comment'] = $service['NAME'] . ' : ' . $comment;
        $arOrder['delivery'] = $service['NAME'];
        $arOrder['package'] = $packageString;
        $arOrder['manager'] = $api -> getClientManager()['guid'];
        $arOrder['company'] = $api -> getFeniksGuid();
        $arOrder['shipment_date'] = $shipmentDate;
        $response = $api -> CreateOrder($arOrder);
        if ($response -> getResponseCode() == '201'){
            $order_guid = $response -> getResponseBody();
            $prop_guid = $propertyCollection->getItemByOrderPropertyCode('order_guid');
            $prop_guid->setValue($order_guid);
            $order -> save();
            ?>
            <div class="order-result">
                <h2 class="order-result__title">
                    Заказ принят
                </h2>
                <div class="order-result__text">
                    Ваш менеджер свяжется с вами в ближайшее время для подтверждения деталей заказа.
                </div>
            </div>
            <?php
            mail('opt@dobriy-jar.ru', 'Новый заказ номер ' . $order -> getId(), 'Создан новый заказ номер ' . $order -> getId());
        } else {
            $error = $response -> getResponseBody();
            print_r($error);
        }


    }
}