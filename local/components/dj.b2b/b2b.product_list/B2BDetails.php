<?php

namespace B2BAjax;
use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\Product\Price;
use Bitrix\Sale\ProductTable;
use Bitrix\Iblock\ElementTable;
class B2BDetails
{   private int $product_id;
    private array $element = array();
    private array $product = array();
    private array $offers = array();
    private array $properties = array();
    private array $ajax_result;

    /**
     * @return array
     */
    public function getAjaxResult(): array
    {
        return $this->ajax_result;
    }


    public function __construct($product_id)
    {
        if (!\CModule::IncludeModule('iblock')){
            throw new Exception('Iblock is not found');
        }
        if (!\CModule::IncludeModule('sale')){
            throw new Exception('Sale is not found');
        }
        $parent_product_id = \CCatalogSku::GetProductInfo($product_id)['ID'];
        if ($parent_product_id){
            $this -> product_id = $parent_product_id;
        } else {
            $this -> product_id = $product_id;
        }
    }

    private function getElementData($product_id): array {
        $arElementData = ElementTable::GetByID($product_id) -> fetch();
        if ($arElementData['DETAIL_PICTURE']){
            $arElementData['DETAIL_PICTURE_PATH'] = \CFile::GetPath($arElementData['DETAIL_PICTURE']);
        }
        return $arElementData;
    }

    public function cmp($a, $b) {
        if ($a['ELEMENT_DATA']['SORT'] == $b['ELEMENT_DATA']['SORT']) {
            return 0;
        }
        return ($a['ELEMENT_DATA']['SORT'] < $b['ELEMENT_DATA']['SORT']) ? -1 : 1;
    }

    public function getProperties($product_id){
        $resElement = \CIBlockElement::GetByID($product_id) -> GetNextElement();
        return $resElement -> GetProperties();
    }

    private function getOffersData($product_id){
        $arOffers = \CCatalogSku::getOffersList($product_id)[$product_id];
        if ($arOffers){
            foreach ($arOffers as $offer_id => $offer){
                $offer['ELEMENT_DATA'] = $this -> getElementData($offer_id);
                $offer['PRODUCT_DATA'] = $this -> getProductData($offer_id);
                $this -> offers[] = $offer;
            }

            usort($this -> offers, array('B2BAjax\B2BDetails', "cmp"));
        }
    }

    private function getProductData($product_id): array {
        $arProductData = ProductTable::GetByID($product_id) -> fetch();
        $resPriceData = PriceTable::getList(array('filter' => array('PRODUCT_ID' => $product_id,
            'CATALOG_GROUP_ID' => [2, 3]), 'select' => array('CATALOG_GROUP_ID', 'PRICE')));
        while ($arPriceData = $resPriceData -> fetch()){
            $arProductData['PRICES'][] = $arPriceData;
        }
        return $arProductData;
    }

    public function getData(){
        $this -> element = $this -> getElementData($this -> product_id);
        $this -> properties = $this -> getProperties($this -> product_id);
        $this -> getOffersData($this -> product_id);
        if (!$this -> offers){
            $this -> product = $this -> getProductData($this -> product_id);
        } else {
            $this -> product['PRICES'] = [['CATALOG_GROUP_ID' => 2, 'PRICE' => 0],
                ['CATALOG_GROUP_ID' => 3, 'PRICE' => 0]];
            foreach ($this -> offers as $offer){
                if ($offer['ELEMENT_DATA']['ACTIVE'] !== 'Y'){
                    continue;
                }
                foreach ($offer['PRODUCT_DATA']['PRICES'] as $price){
                    foreach ($this->product['PRICES'] as &$product_price){
                        if ($product_price['CATALOG_GROUP_ID'] == $price['CATALOG_GROUP_ID']){
                            if ($product_price['PRICE'] > $price['PRICE'] || $product_price['PRICE'] == 0){
                                $product_price['PRICE'] = $price['PRICE'];
                            }
                        }
                    }
                }
            }
        }
        $this -> createViewData();
    }

    public function createViewData(){
        $this -> ajax_result['NAME'] = $this -> element['NAME'];
        $this -> ajax_result['TEXT'] = $this -> element['PREVIEW_TEXT'];
        $this -> ajax_result['PRODUCT'] = $this -> product;
        foreach ($this -> properties as $prop){
            if ($prop['VALUE']){
                $arProps = array('NAME' => $prop['NAME'], 'VALUE' => $prop['VALUE']);
                $this -> ajax_result['PROPS'][$prop['ID']] = $arProps;
            }
        }
        if (!$this -> offers){
            $this -> ajax_result['OFFERS']  = 'N';
            $this -> ajax_result['PICTURE_PATH'] = $this -> element['DETAIL_PICTURE_PATH'];
        } else {
            $this -> ajax_result['OFFERS']  = 'Y';
            $this -> ajax_result['PICTURE_PATH'] = $this -> offers[0]['ELEMENT_DATA']['DETAIL_PICTURE_PATH'];
        }
    }
}