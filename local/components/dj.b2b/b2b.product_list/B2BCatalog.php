<?php

namespace B2BAjax;

use B2BAjax\B2BDetails,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket;
use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use CIBlockElement;

class B2BCatalog
{
    private int $element_pagesize = 20;
    private int $element_offset = 0;
    private int $iblock = 2;
    private array $arBasketItems;
    private array $query_data = [];
    private $order_basket = false;
    private array $arElementsFilter = [];
    private array $arOffersFilter = [];
    private bool $offersFiltered = false;


    private function log_string($string)
    {

        if ($_GET['log'] == 'true') {
            echo '<pre>';
            print_r($string);
            echo '</pre>';
        }
    }

    public function __construct()
    {
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule('iblock');

        if (!$_GET['order']) {
            $basket = Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite());
        } else {
            $order = Order::load($_GET['order']);
            $basket = $order->getBasket();
            $this->order_basket = $basket;
        }
        $basketItems = $basket->getBasketItems();
        foreach ($basketItems as $item) {
            $this->arBasketItems[$item->getProductId()] = $item->getQuantity();
        }
        $this->element_offset = (int)$_GET['offset'];
        $this->query_data = [
            'main' => ($_GET['basket'] ? 'basket' : 'catalog'),
            'property_filter' => [
                'BRAND' => $_GET['BRAND'],
                'b2b_sale' => $_GET['b2b_sale'],
                'promote' => $_GET['promote']
            ],
            'section' => $_GET['sections'],
            'search' => $_GET['search']];
    }

    private function rejectEmpty()
    {
        if (!$this->arElementsFilter) {
            echo '';
            die();
        }
    }

    public function formCatalogJson()
    {
        $arSectionFilter = array();

        // Фильтруем ID элементов по запросу поиска или в корзине.
        // Если поиск находит 0 элементов - возвращается пустая строка
        if ($this->query_data['main'] == 'basket') {
            $this->basketFilter();
        } else if ($this->query_data['search']) {
            $this->searchFilter($this->query_data['search']);
            $this->rejectEmpty();
        }

        $this->log_string($this->query_data);
        // Отфильтровываем ID, не подходящие по описанию параметров
        foreach ($this->query_data['property_filter'] as $property_code => $property_value) {
            if (!$property_value) {
                continue;
            }

            $this->propertyFilter($property_code, $property_value);
            $this->rejectEmpty();
        }

        $arSectionFilter['IBLOCK_ID'] = $this->iblock;
        $arAllSections = array();

// Фильтруем категории по указанной родительской
        if ($this->query_data['section']) {
            $arSectionFilter['SECTION_ID'] = $this->sectionFilter($this->query_data['section'], $arAllSections);
        }

// Добавляем к родительской дочернии категории
        $arSectionSelect = array('ID', 'NAME', 'SORT');
        $resSections = \CIBlockSection::GetList(array('SORT' => 'ASC'), $arSectionFilter, false, $arSectionSelect);
        while ($arSection = $resSections->fetch()) {
            $arAllSections[] = $arSection;
        }

        foreach ($arAllSections as &$section) {
            if (!$this->addSectionProducts($section)) {
                $this->log_string($section['NAME']);
                break;
            }
        }
        if (!$_GET['log']) {
            echo json_encode($arAllSections, JSON_UNESCAPED_UNICODE);
        }
    }

    private
    function searchFilter($query): void
    {
        $arElements = array();
        \CModule::IncludeModule("search");
        $obSearch = new \CSearch;
        $obSearch->Search(array(//при желании, фильтр можете еще сузить, см.документацию
            'QUERY' => $query,
            'MODULE_ID' => 'iblock'
        ));
        while ($row = $obSearch->fetch()) {
            $arElements[] = $row['ITEM_ID'];
        }
        $this->arElementsFilter = $arElements;
    }

    private
    function basketFilter(): void
    {
        $arFilter = [];
        foreach (array_keys($this->arBasketItems) as $basketItemId) {
            $arFilter[] = \CCatalogSku::GetProductInfo($basketItemId)['ID'] ?? $basketItemId;
        }
        $this->arElementsFilter = array_unique($arFilter);
    }

    private
    function propertyFilter($code, $value): void
    {

        $resProperty = PropertyTable::getList(['filter' => ['CODE' => $code], 'select' => ['ID', 'IBLOCK_ID']]);
        while ($arProperty = $resProperty -> fetch())
        {
            $arElements = [];
            //check enum
            $this->log_string($arProperty);
            $this->log_string($value);
            $true_value = $value;
            $resEnum = PropertyEnumerationTable::getList(
                ['filter' => ['PROPERTY_ID' => $arProperty['ID'], 'VALUE' => $value],
                    'select' => ['ID']]);
            while ($arEnum = $resEnum -> fetch()){
                 $true_value = $arEnum['ID'];
            }
            $this->log_string($true_value);

            $resPropertiesValue = ElementPropertyTable::getList(['filter' => ['IBLOCK_PROPERTY_ID' => $arProperty['ID'], 'VALUE' => $true_value],
                'select' => ['IBLOCK_ELEMENT_ID']]);
            while ($arPropertyValue = $resPropertiesValue->fetch()) {
                $arElements[] = $arPropertyValue['IBLOCK_ELEMENT_ID'];
            }
            $this->log_string($arElements);
            if ($arProperty['IBLOCK_ID'] == 2){
                $this->arElementsFilter = $this->arElementsFilter ? array_intersect($this->arElementsFilter, $arElements) : $arElements;
            } elseif ($arProperty['IBLOCK_ID'] == 3){
                $this -> offersFiltered = true;
                $this->arOffersFilter = $this->arOffersFilter ? array_intersect($this->arOffersFilter, $arElements) : $arElements;
            }
        }}


    private
    function sectionFilter($section_id, &$arAllSections): array
    {
        $rsParentSection = \CIBlockSection::GetByID($section_id);
        if ($arParentSection = $rsParentSection->GetNext()) {
            $arrFullListSection = array();
            $arSectionFilter['SECTION_ID'][] = $arParentSection['ID'];
            $arAllSections[] = $arParentSection;
            $arFilter = array('IBLOCK_ID' => $this->iblock,
                '>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
                '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
                '>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL'],
                'ACTIVE' => 'Y'); // выберет потомков без учета активности
            $rsSect = \CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter);
            while ($arSect = $rsSect->GetNext()) {
                $arrFullListSection[] = $arSect['ID'];// получаем подразделы
            }
            return array_merge($arSectionFilter['SECTION_ID'], $arrFullListSection);
        }
        return array();
    }

    public
    function formDetailsJson()
    {
        $b2b_detail = new B2BDetails($_GET['product_id']);
        $b2b_detail->getData();
        echo json_encode($b2b_detail->getAjaxResult(), JSON_UNESCAPED_UNICODE);
    }

    private
    function addSectionProducts(&$section): bool
    {
        $chosen_products = [];
        $arFilter = array('IBLOCK_ID' => $this -> iblock ,'SECTION_ID' => $section['ID'], 'ACTIVE' => 'Y', 'ID' => $this->arElementsFilter);
        $sectionCount = \CIBlockElement::GetList(false, $arFilter, array());
        if ($sectionCount < $this->element_offset) {
            $this->element_offset -= $sectionCount;
        } else {
            $resProducts = \CIBlockElement::GetList(
                array('SORT' => 'ASC'),
                $arFilter,
                false,
                array('nTopCount' => $this->element_pagesize, 'nOffset' => $this->element_offset),
                array('PROPERTY_ARTNUMBER', 'PROPERTY_b2b_available',
                    'ID', 'NAME', 'PRICE_2', 'PRICE_3', 'QUANTITY', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'ACTIVE'));
            while ($arProduct = $resProducts->fetch()) {

                if (!$arProduct['DETAIL_PICTURE']) {
                    $arProduct['DETAIL_PICTURE'] = $arProduct['PREVIEW_PICTURE'];
                }
                if ($arProduct['PROPERTY_B2B_AVAILABLE_VALUE'] === 'Нет') {
                    $arProduct['HIDDEN'] = 'hidden';
                }
                if (\CCatalogSku::IsExistOffers($arProduct['ID'], 2)) {
                    $arOffers = \CCatalogSku::getOffersList($arProduct['ID'], 2);
                    $resOffers = \CIBlockElement::GetList(
                        array('SORT' => 'ASC'),
                        array('ID' => array_map(fn($a) => $a['ID'],
                            $arOffers[$arProduct['ID']]),
                            'ACTIVE' => 'Y'),
                        false,
                        false,
                        array('PROPERTY_ARTNUMBER',
                            'ID',
                            'NAME',
                            'PRICE_3',
                            'PRICE_2',
                            'QUANTITY',
                            'DETAIL_PICTURE')
                    );

                    while ($arOffer = $resOffers->fetch()) {

                        //Получаем b2b_sale
                        $resProperties = CIBlockElement::GetProperty(3, $arOffer['ID'], 'sort', 'asc', array('CODE' => 'b2b_sale'));
                        while ($arProperty = $resProperties->GetNext()) {
                            if (!$arProperty['VALUE']){
                                continue;
                            }
                            $arOffer['b2b_sale'][] = PropertyEnumerationTable::getById(['PROPERTY_ID' => $arProperty['ID'], 'ID' => $arProperty['VALUE']])-> fetch()['VALUE'];
                        }


                        if (count($arProduct['OFFERS']) == 0) {
                            $arOffer['DETAIL_PICTURE_REAL'] = $this->formatImageReal($arOffer['DETAIL_PICTURE']);
                        }
                        $arOffer['DETAIL_PICTURE'] = $this->formatImage($arOffer['DETAIL_PICTURE']);
                        $arOffer['BASKET_QUANTITY'] = $this->arBasketItems[$arOffer['ID']] ?? 0;
                        $this -> getDiscounts($arOffer);
                        $arOffer['PRICE_2'] = $arOffer['RETAIL_DISCOUNTS']['RESULT_PRICE']['DISCOUNT_PRICE'];

                        if ($this -> offersFiltered && !in_array($arOffer['ID'], $this -> arOffersFilter)){
                            $arOffer['HIDDEN'] = 'hidden';
                        }
                        if ($this->query_data['main'] == 'basket') {
                            if (in_array($arOffer['ID'], array_keys($this->arBasketItems))) {
                                if ($this->order_basket) {
                                    $basket_item = $this->order_basket->getExistsItem('catalog', $arOffer['ID']);

                                    $arOffer['PRICE_3'] = $basket_item->getField('PRICE');
                                } else {
                                    $arOffer['PRICE_3'] = $arOffer['WHOLESALE_DISCOUNTS']['RESULT_PRICE']['DISCOUNT_PRICE'];
                                }
                                $arProduct['OFFERS'][] = $arOffer;
                            }
                        } else {
                            $arOffer['PRICE_3'] = $arOffer['WHOLESALE_DISCOUNTS']['RESULT_PRICE']['DISCOUNT_PRICE'];
                            unset($arOffer['WHOLESALE_DISCOUNTS']);
                            unset($arOffer['RETAIL_DISCOUNTS']);
                            $arProduct['OFFERS'][] = $arOffer;
                        }
                        $this->log_string($arOffer);
                    }
                } else {
                    $arProduct['OFFERS'] = 'N';
                    $this -> getDiscounts($arProduct);

                    //Получаем b2b_sale

                    $resProperties = CIBlockElement::GetProperty(2, $arProduct['ID'], 'sort', 'asc', array('CODE' => 'b2b_sale'));
                    while ($arProperty = $resProperties->GetNext()) {
                        if (!$arProperty['VALUE']){
                            continue;
                        }
                        $arProduct['b2b_sale'][] = PropertyEnumerationTable::getById(['PROPERTY_ID' => $arProperty['ID'], 'ID' => $arProperty['VALUE']])-> fetch()['VALUE'];
                    }

                    $arProduct['PRICE_2'] = $arProduct['RETAIL_DISCOUNTS']['RESULT_PRICE']['DISCOUNT_PRICE'];
                    if ($this->order_basket) {
                        $basket_item = $this->order_basket->getExistsItem('catalog', $arProduct['ID']);
                        $arProduct['PRICE_3'] = $basket_item->getPrice();
                    } else {
                        $arProduct['PRICE_3'] = $arProduct['WHOLESALE_DISCOUNTS']['RESULT_PRICE']['DISCOUNT_PRICE'];
                    }
                    unset($arProduct['WHOLESALE_DISCOUNTS']);
                    unset($arProduct['RETAIL_DISCOUNTS']);
                    $this->log_string($arProduct);
                }
                $arProduct['DETAIL_PICTURE'] = $this->formatImage($arProduct['DETAIL_PICTURE']);
                $arProduct['BASKET_QUANTITY'] = $this->arBasketItems[$arProduct['ID']] ?? 0;
                if ($arProduct['OFFERS'] == 'N' || $arProduct['OFFERS']){
                    $chosen_products[] = $arProduct;
                }
            }
            $section['PRODUCTS'] = $chosen_products;
            $this->element_pagesize -= count($chosen_products);
            $this->element_offset = 0;
            if ($this->element_pagesize <= 0) {
                return false;
            }
        }
        return true;
    }

    private function getDiscounts(&$arProduct): void
    {

        $arProduct['WHOLESALE_DISCOUNTS'] = \CCatalogProduct::GetOptimalPrice(
        $arProduct['ID'],
        $this->arBasketItems[$arProduct['ID']] ?? 1,
        array(), 'N', array(
        [
            'PRICE' => $arProduct['PRICE_3'],
            'CURRENCY' => 'RUB',
            'CATALOG_GROUP_ID' => 3],
    ), 'bb'
    );
        $arProduct['RETAIL_DISCOUNTS'] = \CCatalogProduct::GetOptimalPrice(
            $arProduct['ID'],
            $this->arBasketItems[$arProduct['ID']] ?? 1,
            array(2), 'N', array(
            [
                'PRICE' => $arProduct['PRICE_2'],
                'CURRENCY' => 'RUB',
                'CATALOG_GROUP_ID' => 2],
        ), 's1'
        );
    }

    private
    function formatImage($IMAGE_ID)
    {
        return \CFile::ResizeImageGet($IMAGE_ID, array('width' => 100, 'height' => 50));
    }

    private
    function formatImageReal($IMAGE_ID)
    {
        return \CFile::ResizeImageGet($IMAGE_ID, array('width' => 100, 'height' => 300));
    }

}