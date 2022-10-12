<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 * @var $component
 * @var $APPLICATION
 */
?>

<div class="orders-list b2b-block">
    <table>
        <thead>
        <tr>
            <th>Номер заказа</th>
            <th>Дата и время</th>
            <th>Статус</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
        <?if (!$arResult['ORDERS']):?>
        <tr>
            <td colspan="4">Заказов нет</td>
        </tr>
        <?else:
            foreach($arResult['ORDERS'] as $order):?>
        <tr class="order-item">
                <td><a href="/orders/<?=$order['ID']?>"><?=$order['ID']?></a> </td>
                <td><a href="/orders/<?=$order['ID']?>"><?=$order['DATE_INSERT'] -> toString()?></a></td>
                <td><a href="/orders/<?=$order['ID']?>"><div class="order-item__status"><?=$order['1C_DATA']['status']?></div></a></td>
                <td><a href="/orders/<?=$order['ID']?>"><?=$order['1C_DATA']['sum']?></a>&nbsp₽</td>
        </tr>
        <?endforeach;
        endif;?>
        </tbody>
    </table>
</div>