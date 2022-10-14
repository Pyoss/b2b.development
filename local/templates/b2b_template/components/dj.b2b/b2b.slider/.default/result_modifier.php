<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}
$res = CIBlock::GetList(Array(),Array('ACTIVE'=>'Y',"CODE"=>'b2b_slider'), true);
while($ar_res = $res->Fetch())
{$CODE_ID = $ar_res['ID'];}

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

while ($banner = $rsBanners -> Fetch()){
    $arResult['BANNERS'][] = $banner;
    $banners[$banner['ID']] = $banner;
}

CIBlockElement::GetPropertyValuesArray($banners, $CODE_ID, array(), array('NAME'));

foreach ($arResult['BANNERS'] as &$banner){
    $banner['PROPERTIES'] = $banners[$banner['ID']];
    $href = '/catalog/';
    foreach ($banner['PROPERTIES'] as $propery_code => $property){
        if (!$property['VALUE']){
            continue;
        }
        if (!strpos($href, '?')){
            $href .= '?';
        } else {
            $href .= '&';
        }
        $href .= $propery_code . '=' . $property['VALUE'];
        $banner['HREF'] = $href;
    }
}
