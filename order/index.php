<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?php
$APPLICATION->IncludeComponent(
    "dj.b2b:b2b.order.make",
    "",
    array(
    ),
    false
);
?>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");