<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$strTitle = "";
$TOP_DEPTH = $arResult["SECTION"]["DEPTH_LEVEL"];
$CURRENT_DEPTH = $TOP_DEPTH;
?>
<div class="filter-item__label"> Категория
<div id="sections-select" class="section-input section-main">
    <span id="current-select">Все товары</span>
    <div class="section-input__group--root">
        <div class="section-input" data-value=""><span>Все товары</span></div>
<?php
$lastDepth = false;
foreach ($arResult["SECTIONS"] as $arSection) {
    if ($lastDepth && $lastDepth >= $arSection['DEPTH_LEVEL']){
        echo '</div>';
        if ($lastDepth > $arSection['DEPTH_LEVEL']){
            echo str_repeat('</div>', ($lastDepth - $arSection['DEPTH_LEVEL']) * 2);
        }
    }
    if ($lastDepth && $lastDepth < $arSection['DEPTH_LEVEL']){
        echo '<i class="section-input__arrow"></i><div class="section-input__group">';
    }
    $count = $arParams["COUNT_ELEMENTS"] && $arSection["ELEMENT_CNT"] ? "&nbsp;(" . $arSection["ELEMENT_CNT"] . ")" : "";
    $link = '<div class="section-input" data-value="' . $arSection["ID"] . '">' . '<span>' . $arSection["NAME"] . $count . '</span>';
    $lastDepth = $arSection['DEPTH_LEVEL'];
    echo $link;
}?>
    </div>
</div>
</div>
</div>
</div>
</div>