<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var $arResult
 * @var $arParams
 */

$arResult['GRID'] = [
    'HEAD' => ['article' => 'Артикул',
        'picture'=> 'Фото',
        'name' =>'Наименование',
        'price' =>'Цена',
        'retail-price' =>'РРЦ',
        'quantity' =>'Кол-во',
        'sum' =>'Сумма']
];
CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');
$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(),
    Bitrix\Main\Context::getCurrent()->getSite());
$arResult['SUM'] = $basket -> getPrice();