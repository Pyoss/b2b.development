<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 * @var $component
 * @var $APPLICATION
 */
?>

<div class="orders-list b2b-block">
    <h3 class="orders-list__title b2b-block__title">Мои заказы</h3>
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
                <td><?=$order['ID']?></td>
                <td><?=$order['DATE_INSERT'] -> toString()?></td>
                <td><?=$order['1C_DATA']['status']?></td>
                <td><?=$order['1C_DATA']['sum']?>&nbsp</td>
        <?endforeach;
        endif;?>
        </tbody>
    </table>
</div>