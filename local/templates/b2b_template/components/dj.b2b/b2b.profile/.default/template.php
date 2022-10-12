<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */
?>

<div class="section">
    <h1 class="section__title">Мои данные</h1>
    <div class="profile-wrapper b2b-block">
        <div class="profile-photo">
            <img width='200px' height='auto' src="<?=$arResult['PERSONAL_PHOTO']?>">
        </div>
        <div id='profile-form' class="profile">
            <div id='profile-name' class="profile__name"><?=$arResult['NAME']?> <?=$arResult['LAST_NAME']?></div>
            <div id='profile-company' class="profile__company loading"></div>
            <div id='profile-phone' class="profile__phone"><?=$arResult['PHONE']?></div>
            <div id='profile-email' class="profile__email"><?=$arResult['EMAIL']?></div>
            <button id='profile-edit' class="profile__edit">Редактировать профиль</button>
        </div>
    </div>
    <h2 class="section__title">Компания</h2>
    <div class="company-table__wrapper b2b-block">
        <div id="company-table" class="company-table loading"></div>
    </div>
</div>
