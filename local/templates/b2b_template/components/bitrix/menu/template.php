<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult)):?>
    <ul class="footer-link-group">
        <? foreach ($arResult as $arItem):
            if ($arItem['LINK'] == $_SERVER['SCRIPT_URL']):?>
                <div class="sidebar__menu-item--active"><a
                            href="<?= $arItem['LINK'] ?>"><?= $arItem['TEXT'] ?></a><i class="sidebar__arrow"></i>
                </div>
            <? else: ?>
                <div class="sidebar__menu-item"><a href="<?= $arItem['LINK'] ?>"><?= $arItem['TEXT'] ?></a><i
                            class="sidebar__arrow"></i></div>
            <? endif; ?>
        <? endforeach; ?>
    </ul>
<? endif ?>