<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION ->SetTitle('Профиль');

$APPLICATION->IncludeComponent(
    "dj.b2b:b2b.profile",
    ".default",
    array(
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");

?>