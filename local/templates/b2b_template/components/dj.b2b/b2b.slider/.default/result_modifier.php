<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}

$rsBanners = \Bitrix\Iblock\ElementTable::getList([
        'filter' => array(
            'IBLOCK_ID' => 14,
            'ACTIVE' => 'Y'
        ),
        'select' => array(
            'ID', 'DETAIL_PICTURE', 'CODE'
        ),
        'order' => array('SORT' => 'asc')]
);
$index = 0;
while ($banner = $rsBanners -> Fetch()){
    $arResult['BANNERS'][] = $banner;
}