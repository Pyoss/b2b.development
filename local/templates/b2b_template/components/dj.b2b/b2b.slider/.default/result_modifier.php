<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}
$res = CIBlock::GetList(
    Array(),
    Array(
        'ACTIVE'=>'Y',
        "CODE"=>'b2b_slider'), true
);

while($ar_res = $res->Fetch())
{
    $CODE_ID=$ar_res['ID'];
}
print_r($CODE_ID);

$rsBanners = \Bitrix\Iblock\ElementTable::getList([
        'filter' => array(
            'IBLOCK_ID' => $CODE_ID,
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