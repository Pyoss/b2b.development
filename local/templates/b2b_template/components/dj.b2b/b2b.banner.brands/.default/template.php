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

    <div class="banners">
        <?foreach ($arResult['BRANDS'] as $brand):
            ;?>
        <a href="/catalog/?brand=<?=$brand['IBLOCK_ELEMENT_ID']?>">
        <div class="banners__item" style="background-image: url('<?=$brand['PICTURE_DATA']?>')">
        </div>
        </a>
<?endforeach;?>

    </div>


<?php