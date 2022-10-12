<?php
use Bitrix\Main\Page\Asset;

class DJMain
{
    const IMAGE_TEMPLATE_SRC = '/images/template.png';


    public static function replaceProductType($product_type, $name){
        if (!$product_type){
            return $name;
        }
        return str_replace($product_type .
            ' ' , '', $name);
    }

    public function addCss()
    {
        $cssDir = SITE_TEMPLATE_PATH . "/css/";
        $arDirCss = scandir( substr($cssDir , 1));
        foreach ($arDirCss as $сssFile){
            if (pathinfo($сssFile)['extension'] == "css"){
                $APPLICATION -> SetAdditionalCSS($cssDir . $сssFile);
            };
        }
    }

    /**
     * Вывод строки на страницу внутрь тэга <pre>
     * @param $string
     */
    public static function displayString($string){
        global $USER;
        if ($USER->IsAdmin()) {
            print_r('<pre>');
            print_r($string);
            print_r('</pre>');
        }
    }

    /**
     * Вывод строки в консоль JS браузера
     * @param $string
     */
    public static function consoleString($string){
        global $USER;
        if ($USER->IsAdmin()):?>
        <script>
            console.log(<?=CUtil::PhpToJSObject($string)?>);
        </script>
        <?endif;
    }

    /**
     * Поиск полного пути элемента инфоблока
     * @param $string
     */
    public static function getFullUrl($EL_ID){
        $res = CIBlockElement::GetByID($EL_ID);
        if($ar_res = $res->GetNext())
            return $ar_res['DETAIL_PAGE_URL'];
    }

    /**
     * Для формирования правильного падежа слов после числительных
     * @param $value
     * @param $ar_words
     * @return string
     */
    public function formatNum($value, $ar_words): string
    {
        $number = $value % 100;
        if ($number > 19) {
            $number = $number % 10;
        }
        $number_output = $value . ' ';
        switch($number_output) {
            case 1:  $number_output .= $ar_words[0]; break;
            case 2:
            case 3:
            case 4:  $number_output .= $ar_words[1]; break;
            default: $number_output .= $ar_words[2]; break;
        }
        return $number_output;
    }


    public function getSubsectionsArray($arRes){
        $arRes['section_ids'][] = $arRes['cur_id'];
        $rsSections = \Bitrix\Iblock\SectionTable::getList(
            array('select' => array('ID'),
                'filter' => array('IBLOCK_SECTION_ID' => $arRes['cur_id'])));
        while ($section = $rsSections -> Fetch()){
            $arRes['cur_id'] = $section['ID'];
            $arRes = DJMain::getSubsectionsArray($arRes);
        }
        return $arRes;
    }

    /**
     * Получаем минимальную цену в определенной категории
     */
    public function getLowestPrice($section_id){

        $arSections = DJMain::getSubsectionsArray(array('cur_id' => $section_id))['section_ids'];
        global $DB;
        $results = $DB->Query(
            "SELECT b_iblock_element.ID as product_id, MIN(b_catalog_price.PRICE) as price
                FROM b_iblock_element
                LEFT JOIN b_catalog_price ON b_iblock_element.ID=b_catalog_price.PRODUCT_ID
                WHERE b_iblock_element.IBLOCK_SECTION_ID IN (" . implode(',', $arSections) . ")
                 and b_iblock_element.ACTIVE = 'Y'
                 and b_catalog_price.CATALOG_GROUP_ID = 2
                 and price > 100");
        //выполняем произвольный запрос

        while ($row = $results->Fetch())
        {
            return $row['price'];
        }
        return 0;
    }

    public static function getSortOffer($ID, $arProp){

        $offers = CCatalogSKU::getOffersList($ID, 2, array('ACTIVE' => 'Y'),
            array('ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'SORT'), $arProp)[2326];
        if ($offers){
            $min_sort = null;
            foreach ($offers as $item_id => $offer){
                if (!$min_sort || $offer['SORT'] < $min_sort['SORT']){
                    $min_sort = $offer;
                }
            }
            return $min_sort;
        }
        return false;
    }

    public static function getPreviewPictureSrc($arItem){

        if ($arItem['PREVIEW_PICTURE']){
            if (is_numeric($arItem['PREVIEW_PICTURE'])){
                return CFile::GetPath($arItem['PREVIEW_PICTURE']);
            } else {
               return $arItem['PREVIEW_PICTURE'];
            }
        }

        if ($arItem['DETAIL_PICTURE']) {
            if (is_numeric($arItem['DETAIL_PICTURE'])) {
                return CFile::GetPath($arItem['DETAIL_PICTURE']);
            } else {
                return $arItem['DETAIL_PICTURE'];
            }
        }

        $sortOffer = DJMain::getSortOffer($arItem['ID'], array());
        if ($sortOffer){
            return DJMain::getPreviewPictureSrc($sortOffer);
        }
        return DJMain::IMAGE_TEMPLATE_SRC;
    }

}