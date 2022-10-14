<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var $arResult
 * @var $arParams
 */

$arResult['GRID'] = [
    'HEAD' => ['article' => 'Артикул',
        'picture' => 'Фото',
        'name' => 'Наименование',
        'sale' => 'Акция',
        'price' => 'Цена,&nbsp<span style="font-weight:400">р</span>',
        'retail-price' => 'РРЦ,&nbsp<span style="font-weight:400">р</span>',
        'margin' => 'Наценка,&nbsp<span style="font-weight:400">р</span>',
        'quantity' => 'Кол-во',
        'sum' => 'Сумма,&nbsp<span style="font-weight:400">р</span>']
];

//Получаем значения из запроса
$arResult['CURRENT_BRAND_ID'] = $_GET['brand'];
$arResult['CURRENT_SALE_NAME'] = $_GET['sale'];
$arResult['CURRENT_SECTION_ID'] = $_GET['section'];

//Получаем возможные значения фильтров
$saleId = \Bitrix\Iblock\PropertyTable::getList(['filter' => ['CODE' => ['b2b_sale']], 'select' => ['ID']]) -> fetch()['ID'];
$rsSale = \Bitrix\Iblock\PropertyEnumerationTable::getList(['filter' => ['PROPERTY_ID' => $saleId], 'select' =>['VALUE', 'ID']]);
while ($arSale = $rsSale -> fetch()){
    $arResult['SALE'][] = $arSale;
}

$arResult['BRANDS'] = [['ID' => 0, 'NAME' => 'Все']];
$rsBrands = \Bitrix\Iblock\ElementTable::getList(['filter' => ['IBLOCK_ID' => 6, 'ACTIVE' => 'Y'], 'select' =>['NAME', 'ID']]);
while ($arBrands = $rsBrands -> fetch()){
    $arResult['BRANDS'][] = $arBrands;
}