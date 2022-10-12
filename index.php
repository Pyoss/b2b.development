<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
    <div class="section">
        <h1 class="section__title">Мой кабинет</h1>
        <div class="b2b-main-columns">
            <?
            $APPLICATION -> IncludeComponent(
        "dj.b2b:b2b.order.list",
        ".default",
        array(),
        false
            );
            ?>
            <div>
                <div class="tutorial-block b2b-block">
                    <h3 class="tutorial-block__title b2b-block__title">Как пользоваться порталом?</h3>
                    <div class="video-wrapper"></div>
                </div>
                <div class="documents-block b2b-block">
                    <h3 class="documents-block__title__title b2b-block__title">Мои документы</h3>
                </div>
            </div>
        </div>
    </div>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>