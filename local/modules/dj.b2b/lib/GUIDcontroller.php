<?php

namespace DJ\B2B;
use Bitrix\Main\Loader;

Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class GUIDController
    {
    private $entity_data_class;

    public function __construct(){
        $hlbl = 3; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $this -> entity_data_class = $entity->getDataClass();
    }

    public function getRowByGUID($GUID){
        $rsSelect = $this -> entity_data_class::getList(array(
            "select" => array("*"),
            "filter" => array("UF_GUID" => $GUID)
        ));
        return $rsSelect->Fetch();
    }

    public function updateRow(array $product, array $row){
        /*if (
            $product['available'] != $row['UF_PRODUCT_QUANTITY'] ||
            $product['trade_price'] != $row['UF_ELEMENT_STOCK_PRICE'] ||
            $product['retail_price'] != $row['UF_ELEMENT_RETAIL_PRICE'] ||
            $product['UF_OUTDATED'] == 'Y')
        {*/
            $row['UF_ELEMENT_RETAIL_PRICE'] = $product['retail_price'];
            $row['UF_PRODUCT_QUANTITY'] = $product['available'];
            $row['UF_ELEMENT_STOCK_PRICE'] = $product['trade_price'];
            $row['UF_OUTDATED'] = 'Y';
            $this->entity_data_class::update($row['ID'], $row);
            return true;
        /*} else {
            $row['UF_OUTDATED'] = 'N';
            $this->$entity_data_class::update($row['ID'], $row);
            return false;
        }*/
    }

    public function addGuid($iblock_id, $element_id, $guid, $name){
        $this -> entity_data_class::add(array(
            "UF_ELEMENT_ID" => $element_id,
            "UF_IBLOCK_ID" => $iblock_id,
            "UF_GUID" => $guid,
            "UF_1C_PRODUCT_NAME" => $name
        ));
    }

    public function addElementID($id, $data){
        $this -> entity_data_class::update($id, $data);
    }

    public function getAllGuidsRes(){
        return $this -> entity_data_class::getList(array(
            "select" => array("*")
        ));
    }
}