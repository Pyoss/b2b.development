<?php

use Bitrix\Main\Application;
use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();
$eventManager->unRegisterEventHandler("main", "OnAdminIBlockElementEdit", "seo", "\\Bitrix\\Seo\\AdvTabEngine", "eventHandler");
$eventManager = Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler(
    "sale",
    "DiscountonAfterAdd",
    'updateDiscountProps'
);
$eventManager->addEventHandler(
    "sale",
    "DiscountonAfterUpdate",
    'updateDiscountProps'
);
$eventManager->addEventHandler("sale",
    "DiscountonAfterDelete",
    'updateDiscountProps'
);

function updateDiscountProps(\Bitrix\Main\Event $event)
{
    foreach ([2, 3] as $iblockID) {

        $obProp = CIBlockProperty::GetList(
            array(),
            array(
                "IBLOCK_ID" => $iblockID,
                "XML_ID" => 'b2b_sale'
            )
        );

        if ($obProp->SelectedRowsCount() <= 0) {
            $arFields = array(
                "NAME" => 'Акции B2B',
                "ACTIVE" => "Y",
                "SORT" => '99999',
                "CODE" => 'b2b_sale',
                "FILTRABLE" => "Y",
                "MULTIPLE" => "Y",
                "IBLOCK_ID" => $iblockID,
                "XML_ID" => 'b2b_sale',
                "PROPERTY_TYPE" => "L",
                "VALUES" => array(
                    array(
                        "VALUE" => 'Скидка',
                        "DEF" => "N",
                        "SORT" => 100,
                    ),

                    array(
                        "VALUE" => 'Хит',
                        "DEF" => "N",
                        "SORT" => 200,
                    ),

                    array(
                        "VALUE" => 'Новинка',
                        "DEF" => "N",
                        "SORT" => 300,
                    )
                )
            );

            $ibp = new CIBlockProperty;
            $propID = $ibp->Add($arFields);
        } else {
            $prop = $obProp->Fetch();
            $propID = $prop['ID'];
        }


        if ($propID > 0) {
            $enumID = CIBlockPropertyEnum::GetList(
                array(),
                array(
                    'VALUE' => 'Скидка',
                    'PROPERTY_ID' => $propID
                )
            );

            if ($valID = $enumID->Fetch()) {
                $valID = $valID['ID'];

                //все пользователи
                $saleGroups = array(2);
                $prices = array(3);

                $bxCatalog = CIBlockElement::GetList(
                    array(),
                    array('IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y'),
                    false,
                    false,
                    array('ID')
                );

                while ($arElement = $bxCatalog->Fetch()) {
                    $ELEMENT_ID = $arElement['ID'];
                    $arDiscounts = CCatalogDiscount::GetDiscountByProduct(
                        $ELEMENT_ID,
                        $saleGroups,
                        "N",
                        $prices,
                        'bb'
                    );
                    $arProperties = [];
                    writeLog($valID);
                    writeLog($ELEMENT_ID);
                    $resProperties = CIBlockElement::GetProperty($iblockID, $ELEMENT_ID, 'sort', 'asc', array('CODE' => 'b2b_sale'));
                    while ($arPropertyValue = $resProperties->GetNext()) {
                        $arProperties[] = $arPropertyValue['VALUE'];
                    }
                    if (is_array($arDiscounts) && count($arDiscounts) > 0) {
                        if (!in_array($valID, $arProperties)) {
                            $arProperties[] = $valID;
                        }
                        writeLog($arProperties);
                    } else {
                        $arProperties = array_diff($arProperties, [$valID]);
                        if (!$arProperties){
                            $arProperties = false;
                        }
                    }
                    CIBlockElement::SetPropertyValuesEx(
                        $ELEMENT_ID,
                        $iblockID,
                        array(
                            "b2b_sale" => $arProperties,
                        )
                    );
                }
            } else {
                CAdminMessage::ShowMessage('Не найдено значение свойства');
            }
        } else {
            CAdminMessage::ShowMessage('Отсуствует свойство Товар со скидкой');
        }
    }
}

function writeLog($data, $title = 'logs', $file = "/home/bitrix/www/b2b/app.log")
{
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
    $log .= "\n------------------------\n";
    file_put_contents($file, $log, FILE_APPEND);
    return true;
}

$CLASSES_DIR = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/';

// Тестируем необходимые классы (список не окончательный)
require_once $CLASSES_DIR . 'main.php';

