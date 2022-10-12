<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION ->SetTitle('Заказы');
?>
<div class="section">
<?php
$APPLICATION->IncludeComponent(
    "dj.b2b:b2b.orders",
    "",
    Array(
        "ADD_SECTIONS_CHAIN" => "Y",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "DETAIL_SET_BROWSER_TITLE" => "Y",
        "DETAIL_SET_META_DESCRIPTION" => "Y",
        "DETAIL_SET_META_KEYWORDS" => "Y",
        "DETAIL_SET_PAGE_TITLE" => "Y",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FILE_404" => "",
        "MESSAGE_404" => "",
        "SEF_FOLDER" => "/orders",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => array(
            "application_details"=>"#ORDER_ID#/",
            "application_list"=>"",
        ),
        "SET_STATUS_404" => "Y",
        "SHOW_404" => "Y"
    )
);
?>
</div>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>