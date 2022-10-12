<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$APPLICATION->IncludeComponent(
    "dj.b2b:b2b.basket.ajax",
    ".default",
    array(
    ),
    false,array('HIDE_ICONS' => 'Y')
);