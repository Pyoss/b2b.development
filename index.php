<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
?>
<?php $APPLICATION->IncludeComponent(
        'dj.b2b:b2b.slider',
        '',
        array(),
        false); ?>
<?php $APPLICATION->IncludeComponent(
    'dj.b2b:b2b.banner.brands',
    '',
    array(),
    false); ?>

    <div class="section">
        <h1 class="section__title">Мой кабинет</h1>
        <div class="b2b-main-columns">
            <?
            $APPLICATION->IncludeComponent(
                "dj.b2b:b2b.order.list",
                "profile",
                array(),
                false
            );
            ?>
            <div>
                <div class="tutorial-block b2b-block">
                    <h3 class="tutorial-block__title b2b-block__title">О компании</h3>
                    <div class="video-wrapper"><iframe width="100%" height="100%" src="https://www.youtube.com/embed/AaNL7tBChy0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                </div>
            </div>
        </div>
    </div>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>