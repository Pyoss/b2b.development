<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */
?>

<div class="personal-preview">
    <img height=80px width=72px src="/src/figma-images/no_picture.png" class="personal-preview__portrait">
    <div class="personal-preview__name"><?=$arResult['NAME']?> <?=$arResult['LAST_NAME']?></div>
    <div class="personal-preview__company"><?=$arResult['COMPANY_DATA']['name']?></div>
    <div class="exit"></div>
</div>
