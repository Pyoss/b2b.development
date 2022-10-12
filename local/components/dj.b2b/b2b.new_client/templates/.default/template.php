<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */
?>

<section class="section">
    <h1 class="section__title">
        Создание клиента
    </h1>
    <div class="inn-input">
        <label for="client-inn">Введите ИНН клиента</label>
        <input id="client-inn" type="text" class="inn-input__input" placeholder="ИНН">
        <button class="inn-input__button" id="inn-search">Проверить</button>
    </div>
    <div class="client-error" id="client-error">

    </div>

    <div class="client-data hidden" id="client-data">
        <span class="client-data__title">Данные клиента:</span>
        <div class="client-data__row" id="client-name"></div>
        <div class="client-data__row" id="client-email"></div>
        <div class="client-data__row" id="client-act_address"></div>
        <div class="client-data__row" id="client-reg_address"></div>
        <div class="client-data__row" id="client-phone"></div>
    </div>
    <div class="client-input hidden" id="client-input">
        <span class="client-data__title">Регистрационные данные:</span>
        <div class="client-input__row">
            <label for="input-tel">Телефон:</label>
            <input type="tel" class="client-input__input" id="input-tel">
        </div>
        <div class="client-input__row">
            <label for="input-name">Имя:</label>
            <input type="text" class="client-input__input" id="input-name">
        </div>
        <div class="client-input__row">
            <label for="input-mail">Почта:</label>
            <input type="email" class="client-input__input" id="input-mail">
        </div>
        <button class="client-input__button" type="button" id="create-client">Создать</button>
    </div>

</section>
