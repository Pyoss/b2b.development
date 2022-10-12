<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


?>

    <div class="slider">
        <div class="slider__track owl-carousel">

            <? foreach ($arResult['BANNERS'] as $item) {
                ?><div class="slider__slide" style="background-image: url(<?=CFile::GetPath($item['DETAIL_PICTURE'])?>)"></div><?
            } ?>
        </div>
    </div>


<?php