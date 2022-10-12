<?php

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');?>

<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET; ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $APPLICATION->ShowTitle() ?></title>
<?php
$APPLICATION->ShowHead();

$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/normalize.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
\Bitrix\Main\Page\Asset::getInstance() -> addJs(SITE_TEMPLATE_PATH . '/js/Dadata_api.js');
?>
    <main>
        <?php
$APPLICATION -> IncludeComponent('dj.b2b:b2b.register', '.default', array());