<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var $arResult
 * @var $arParams
 */

$arResult['GRID'] = [
    'HEAD' => ['article' => 'Артикул',
        'picture' => 'Фото',
        'name' => 'Наименование',
        'price' => 'Цена',
        'retail-price' => 'РРЦ',
        'margin' => 'Наценка',
        'quantity' => 'Кол-во',
        'sum' => 'Сумма']
];
$arResult['BRANDS'] = [['ID' => 0, 'NAME' => 'Все']];
$rsBrands = \Bitrix\Iblock\ElementTable::getList(['filter' => ['IBLOCK_ID' => 6, 'ACTIVE' => 'Y'], 'select' =>['NAME', 'ID']]);
while ($arBrands = $rsBrands -> fetch()){
    $arResult['BRANDS'][] = $arBrands;
}
$arResult['CURRENT_BRAND_ID'] = $_GET['brand'];