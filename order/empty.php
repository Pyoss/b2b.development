<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION ->SetTitle('Корзина');
?>
<div class="section">
    <div class="section-alert">Ваша корзина пуста</div>
    <div><a href="/catalog/">Перейти в каталог</a> </div>
</div>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");