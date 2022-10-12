<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

    if (!empty($arResult)):?>
<nav class="sidebar__menu">
            <? foreach ($arResult as $arItem):
                if ($arItem['LINK'] == $_SERVER['SCRIPT_URL']):?><a
                    href="<?= $arItem['LINK'] ?>">
                    <div class="sidebar__menu-item--active"><?= $arItem['TEXT'] ?><i class="sidebar__arrow"></i>
                    </div></a>
                <? else: ?>
                    <a href="<?= $arItem['LINK'] ?>">
                    <div class="sidebar__menu-item"><?= $arItem['TEXT'] ?><i
                                class="sidebar__arrow"></i></div>
                    </a>
                <? endif; ?>
            <? endforeach; ?>
    </nav>
    <? endif ?>
