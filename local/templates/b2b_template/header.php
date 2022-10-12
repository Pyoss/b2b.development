<?php
?>
<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET; ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $APPLICATION->ShowTitle() ?></title>
    <?php
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/normalize.css/");
    $APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");


    use \Bitrix\Conversion\Internals\MobileDetect;

    $detect = new MobileDetect;
    if($detect->isMobile())
    {
        ?> Приносим извинения, мобильная версия сайта находится в разработке.<?
        die();
    }

    use Bitrix\Main\Page\Asset;
    use DJ\B2B\Applications\B2BMain;

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/1c_api.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery-3.6.0.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/owl.carousel.js");

    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/owl.carousel.min.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/owl.theme.default.min.css");

    $APPLICATION->ShowHead();
    CModule::IncludeModule('dj.b2b');
    // Здесь мы проверяем доступ пользователя к определенной группе. Если пользователя нет в группе
    // оптовых клиентов или пользователь не зарегистрирован - происходит редирект на страницу авторизации
    global $USER;
    if (!B2BMain::isClient($USER) && !B2BMain::isManager($USER)) {
        LocalRedirect('https://' . SITE_SERVER_NAME . '/auth/?path=' . explode('/', $_SERVER['REQUEST_URI'])[1]);
    }
    ?>
    <div style="display: none" id="panel"><?php
        if ($_GET['panel'] !== 'hidden') $APPLICATION->ShowPanel(); ?></div>
</head>
<body>
<header class="header" id="header">
    <a class="catalog-button" href="/catalog">Каталог продукции</a>
    <?
    $APPLICATION->IncludeComponent(
        "dj.b2b:b2b.basket.header",
        ".default",
        array(),
        false
    );
    ?>
</header>
<div class="sidebar">
    <div class="sidebar__header">
        <div class="logo-container"></div>
    </div>
    <?
    $APPLICATION->IncludeComponent(
        "dj.b2b:b2b.header.profile",
        ".default",
        array(),
        false
    );
    ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:menu',
        'b2b_client',
        [
            'COMPONENT_TEMPLATE' => 'b2b_client',
            'ROOT_MENU_TYPE' => B2BMain::isManager($USER) ? 'b2b_manager' : 'b2b_client',
            'MENU_CACHE_TYPE' => 'N',
            'MENU_CACHE_TIME' => '3600',
            'MENU_CACHE_USE_GROUPS' => 'Y',
            'MENU_CACHE_GET_VARS' => [
            ],
            'MAX_LEVEL' => '1',
            'USE_EXT' => 'N',
            'DELAY' => 'N',
            'ALLOW_MULTI_SELECT' => 'N',
            'FOOTER_MENU_NAME' => 'Клиенты B2B'
        ],
        false
    );
    if (B2BMain::isClient($USER)):
        $APPLICATION->IncludeComponent(
            "dj.b2b:b2b.header.manager",
            ".default",
            array(),
            false
        );endif; ?>
</div>
<main>