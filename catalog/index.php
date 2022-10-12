<?php
if ($_GET['AJAX']){
    require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
    $arHide = array("HIDE_ICONS" => "Y");
} else {
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $arHide = array("HIDE_ICONS" => "N");
}

$APPLICATION->IncludeComponent(
	"dj.b2b:b2b.product_list", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PROPERTY_ID" => array(
			0 => "9",
			1 => "34",
		)
	),
	false,
	array(
		
	)
);

if (!$_GET['AJAX']) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
}
?>