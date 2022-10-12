<?php
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if ($arResult['APPLICATION_DATA']['STATUS'] == 'PENDING') {
    $appClass = 'pending';
    $statusString = 'Новая заявка';
} elseif ($arResult['APPLICATION_DATA']['STATUS'] == 'APPROVED') {
    $appClass = 'approved';
    $statusString = 'Заявка одобрена';
} elseif ($arResult['APPLICATION_DATA']['STATUS'] == 'DENIED') {
    $appClass = 'denied';
    $statusString = 'Заявка отклонена';
}
?>
<div class="section">
    <a class="nav_back" href="/applications/">К списку заявок</a>
    <h1 class="section__title">
        Заявка № <?=$arResult['APPLICATION_DATA']['ID']?> (<?=$statusString?>)
    </h1>
    <div class="app b2b-block">
        <?foreach ($arResult['APPLICATION_GRID'] as $row_name => $row_value):?>
        <div class="app__row">
            <div class="app__row-name"><?=$row_name?></div>
            <div class="app_row-value"><?=$row_value?></div>
        </div>
        <?endforeach;?>
        <? if($arResult['APPLICATION_DATA']['STATUS'] == 'PENDING'):?>
        <div class="app__buttons">
            <button class="app__button-confirm">Подтвердить</button>
            <button class="app__button-deny">Отклонить</button>
        </div>
        <? endif ?>
    </div>
</div>