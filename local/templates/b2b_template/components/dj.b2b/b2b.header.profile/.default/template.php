<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */
?>

<div class="personal-preview">
    <img height=90px width=84   px src="/src/figma-images/no_picture.png" class="personal-preview__portrait">
    <div class="personal-preview__name"><?=$arResult['NAME']?> <?=$arResult['LAST_NAME']?></div>
    <div class="personal-preview__company"><?=$arResult['COMPANY_DATA']['name']?></div>
    <a href="?logout=true"><div class="exit"></div></a>
</div>
