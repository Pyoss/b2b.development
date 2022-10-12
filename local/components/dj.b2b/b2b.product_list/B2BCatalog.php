<?php

namespace B2BAjax;

use B2BAjax\B2BDetails,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket;

class B2BCatalog
{
    private int $element_pagesize = 20;
    private int $element_offset = 0;
    private int $iblock = 2;
    private array $arBasketItems;
    private array $filters = [];

    public function __construct()
    {
        \CModule::IncludeModule("sale");
        if (!$_GET['order']) {
            $basket = Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite());
        } else {
            $order = Order::load($_GET['order']);
            $basket = $order -> getBasket();
        }
        $basketItems = $basket->getBasketItems();
        foreach ($basketItems as $item) {
            $this->arBasketItems[$item->getProductId()] = $item->getQuantity();
        }
    }

    private function searchFilter($query): array
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
        return $arElements;
    }

    private function basketFilter(): array
    {
        $arFilter = [];
        foreach (array_keys($this->arBasketItems) as $basketItemId) {
            $arFilter[] = \CCatalogSku::GetProductInfo($basketItemId)['ID'] ?? $basketItemId;
        }
        return array_unique($arFilter);
    }

    private function sectionFilter($section_id, &$arAllSections): array
    {

        /* ================================ */
        /* ПОЛНЫЙ СПИСОК ПОДРАЗДЕЛОВ УКАЗАННОГО $arSectionFilter РАЗДЕЛА */
        /* ================================ */
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

    private function addSectionProducts($arElementsFilter, &$section): bool
    {
        $chosen_products = [];
        $arFilter = array('SECTION_ID' => $section['ID'], 'ACTIVE' => 'Y', 'ID' => $arElementsFilter);
        $sectionCount = \CIBlockElement::GetList(false, $arFilter, array());
        if ($sectionCount < $this->element_offset) {
            $this->element_offset -= $sectionCount;
        } else {
            $resProducts = \CIBlockElement::GetList(
                array('SORT' => 'ASC'),
                $arFilter,
                false,
                array('nTopCount' => $this->element_pagesize, 'nOffset' => $this->element_offset),
                array('PROPERTY_ARTNUMBER', 'ID', 'NAME', 'PRICE_2', 'PRICE_3', 'QUANTITY', 'DETAIL_PICTURE', 'ACTIVE'));
            while ($arProduct = $resProducts->fetch()) {
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
                        if (count($arProduct['OFFERS']) == 0) {
                            $arOffer['DETAIL_PICTURE_REAL'] = $this->formatImageReal($arOffer['DETAIL_PICTURE']);
                        }
                        $arOffer['DETAIL_PICTURE'] = $this->formatImage($arOffer['DETAIL_PICTURE']);
                        $arOffer['BASKET_QUANTITY'] = $this->arBasketItems[$arOffer['ID']] ?? 0;
                        if (in_array('basket', $this->filters)) {
                            if (in_array($arOffer['ID'], array_keys($this->arBasketItems))) {
                                $arProduct['OFFERS'][] = $arOffer;
                            }
                        } else {
                            $arProduct['OFFERS'][] = $arOffer;
                        }
                    }
                } else {
                    $arProduct['OFFERS'] = 'N';
                }
                $arProduct['DETAIL_PICTURE'] = $this->formatImage($arProduct['DETAIL_PICTURE']);
                $arProduct['BASKET_QUANTITY'] = $this->arBasketItems[$arProduct['ID']] ?? 0;
                $chosen_products[] = $arProduct;
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

    private function formatImage($IMAGE_ID)
    {
        return \CFile::ResizeImageGet($IMAGE_ID, array('width' => 100, 'height' => 50));
    }

    private function formatImageReal($IMAGE_ID)
    {
        return \CFile::ResizeImageGet($IMAGE_ID, array('width' => 100, 'height' => 300));
    }

    public function formCatalogJson()
    {
        $this->element_offset = (int)$_GET['offset'];
        $section_id = (int)$_GET['sections'];
        $search = $_GET['search'];
        $basket = $_GET['basket'];
        $order = $_GET['order'];
        \CModule::IncludeModule('iblock');
        $arSectionFilter = array();

        // Фильтруем ID элементов по запросу поиска
        if ($search) {
            $arElementsFilter = $this->searchFilter($search);
        } else if ($basket) {
            $this->filters[] = 'basket';
            $arElementsFilter = $this->basketFilter();
        } else if ($order) {
            $this->filters[] = 'basket';
            $arElementsFilter = $this->basketFilter();
        }
        $arSectionFilter['IBLOCK_ID'] = $this->iblock;

        $arAllSections = array();

        // Фильтруем категории по указанной родительской
        if ($section_id) {
            $arSectionFilter['SECTION_ID'] = $this->sectionFilter($section_id, $arAllSections);
        }

        // Добавляем к родительской дочернии категории
        $arSectionSelect = array('ID', 'NAME', 'SORT');
        $resSections = \CIBlockSection::GetList(array('SORT' => 'ASC'), $arSectionFilter, false, $arSectionSelect);
        while ($arSection = $resSections->fetch()) {
            $arAllSections[] = $arSection;
        }

        foreach ($arAllSections as &$section) {
            if (!$this->addSectionProducts($arElementsFilter, $section)) {
                break;
            }
        }
        echo json_encode($arAllSections, JSON_UNESCAPED_UNICODE);
    }

    public function formDetailsJson()
    {
        $b2b_detail = new B2BDetails($_GET['product_id']);
        $b2b_detail->getData();
        echo json_encode($b2b_detail->getAjaxResult(), JSON_UNESCAPED_UNICODE);
    }
}