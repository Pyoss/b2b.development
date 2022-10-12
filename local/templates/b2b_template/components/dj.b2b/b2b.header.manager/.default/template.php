<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */

if ($arResult['MANAGER_ID'] == 12490):
?>

<div class="manager-block">
    <span class="manager-block__title">Ваш менеджер</span>
    <div class="manager-block__snippet">
        <img width='70px' height='auto' src="/src/figma-images/halin.png" class="manager-block__portrait">
        <span class="manager-block__name">Алексей Халин</span>
    </div>
    <span>halin@dobriy-jar.ru</span><br>
    <span>+7 (906) 078-76-16</span><br>
</div>
<?php else: ?>
<div class="manager-block">
    <span class="manager-block__title">Ваш менеджер</span>
    <div class="manager-block__snippet">
        <img width='70px' height='auto' src="/src/figma-images/no_picture.png" class="manager-block__portrait">
        <span class="manager-block__name">Константин Грибчатых</span>
    </div>
    <span>k.gribchatykh@dobriy-jar.ru</span><br>
    <span>+7 (966) 317 52-38</span><br>
</div>
<?php endif;?>