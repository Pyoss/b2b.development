<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */
?>

<div class="auth-wrapper">
    <div id="auth-module" class="auth reg">
        <form method="post">
            <div class="auth__header">Заявка</div>
            <div class="auth__section-title">Менеджер</div>
            <label for="FIO">
                <input type="text" name="FIO" size="100px" placeholder="ФИО" required>
                <span class="hint">ФИО</span>
            </label>
            <label for="email">
                <input type="email" name="email" size="100px" placeholder="Email" id="email" required>
                <span class="hint">Email</span>
            </label>
            <label for="tel">
                <input type="tel" name="tel" size="100px" placeholder="Телефон" id="tel" required>
                <span class="hint">Телефон</span>
            </label>
            <div class="auth__section-title">Компания</div>
            <label for="inn">
                <input type="text" name="inn" size="100px" placeholder="ИНН" id="inn" required>
                <span class="hint">ИНН</span>
                <i class="fa fa-search inn-search" id="inn-search"></i>
            </label>
            <div class="auth__company-type">
                <label for="IP">
                    <input type="radio" name="company-type" id="IP" value="IP" required>ИП</label>
                <label for="OOO">
                    <input type="radio" name="company-type" id="OOO" value="OOO" required>ООО</label>
            </div>
            <label for="ogrn">
                <input type="text" name="ogrn" size="100px" placeholder="ОГРН" id="ogrn" required>
                <span class="hint">ОГРН</span>
            </label>
            <label for="company-name">
                <input type="text" name="company-name" size="100px" placeholder="Название компании" id="company-name" required>
                <span class="hint">Название компании</span>
            </label>
            <label for="act-address">
                <input type="text" name="act-address" size="100px" placeholder="Факт.Адрес" id="act-address" required>
                <span class="hint">Факт.Адрес</span>
            </label>
            <label for="reg-address">
                <input type="text" name="reg-address" size="100px" placeholder="Юр.Адрес" id="reg-address" required>
                <span class="hint">Юр.Адрес</span>
            </label>
            <div class="auth__section-title">Банковские данные</div>
            <label for="num">
                <input type="text" name="num" size="100px" placeholder="Номер счета" id="num" required>
                <span class="hint">Номер счета</span>
            </label>
            <label for="bik">
                <input type="text" name="bik" size="100px" placeholder="БИК" id="bik" required>
                <span class="hint">БИК</span>
            </label>
            <label for="kor">
                <input type="text" name="kor" size="100px" placeholder="КОР" id="kor" required>
                <span class="hint">КОР</span>
            </label>
            <button class="auth__submit" type="submit">Регистрация</button>
            <div class="auth__details">
                <span>Уже есть аккаунт? <a href="/auth/">Войти</a></span>
            </div>
        </form>
    </div>
</div>