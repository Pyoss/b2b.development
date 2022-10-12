<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues Текущие выставленные значения параметров */
/** @var array $arComponentParameters Настройки параметров */
/** @var array $componentPath Путь к компоненту */

CModule::IncludeModule("iblock");


$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array());

$rsProperties = \Bitrix\Iblock\PropertyTable::getList(array(
    'filter' => array(
        'IBLOCK_ID' => 2,
    ),
    'select' => array(
        'ID',
        'NAME',
    ),
));

while ($prop = $rsProperties->fetch()) {
    $arPropParams[$prop['ID']] = $prop['NAME'];
}

$arComponentParameters['PARAMETERS']['PROPERTY_ID'] = array(
    "PARENT" => 'BASE',
    "NAME" => 'Категории',
    "TYPE" => "LIST",
    "MULTIPLE" => "Y",
    "VALUES" => $arPropParams
);