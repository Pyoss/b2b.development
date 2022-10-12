<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION ->SetTitle('Счета');
?>
<?php
$APPLICATION->IncludeComponent(
    "dj.b2b:b2b.payments",
    ".default",
    array(),
    false
);
?>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");