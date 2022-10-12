<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */

foreach ($arResult['PAYMENT_RESULT'] as $row){
    $row['payment_status'] = $row['payment_status'] == 'paid' ? 'Оплачен' : 'Не оплачен';
    $arResult['GRID']['ROWS'][] = array(
        'payment_name' => $row['name'],
        'payment_price' => $row['sum'],
        'payment_guid' => $row['guid'],
        'payment_status' => $row['payment_status']
    );
}
?>