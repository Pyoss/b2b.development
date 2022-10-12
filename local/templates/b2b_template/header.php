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

    use Bitrix\Main\Page\Asset;
    use DJ\B2B\Applications\B2BMain;

    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/1c_api.js");
    $APPLICATION->ShowHead();
    CModule::IncludeModule('dj.b2b');
    // Здесь мы проверяем доступ пользователя к определенной группе. Если пользователя нет в группе
    // оптовых клиентов или пользователь не зарегистрирован - происходит редирект на страницу авторизации
    global $USER;
    if (!B2BMain::isClient($USER) && !B2BMain::isManager($USER)) {
        LocalRedirect('https://' . SITE_SERVER_NAME . '/auth/');
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
    if (B2BMain::isClient($USER)):?>
        <div class="manager-block">
            <span class="manager-block__title">Ваш менеджер</span>
            <div class="manager-block__snippet">
                <img width='70px' height='auto' src="/src/figma-images/halin.png" class="manager-block__portrait">
                <span class="manager-block__name">Алексей Халин</span>
            </div>
            <span>halin@dobriy-jar.ru</span>
            <span>8 800 555 55 55</span>
            <span>+7 (4722) 20-55-33</span>
        </div>
    <? endif; ?>
</div>
<main>