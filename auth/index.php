<?php

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
use DJ\B2B\Applications\B2BMain;
$APPLICATION ->SetTitle('Авторизация');
CModule::IncludeModule('dj.b2b');
?>

<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET; ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $APPLICATION->ShowTitle() ?></title>
<?php
global $USER;
$APPLICATION->ShowHead();
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/normalize.css");
$APPLICATION->SetAdditionalCSS("/auth/auth.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
\Bitrix\Main\Page\Asset::getInstance() -> addJs("/auth/script.js");
$error = '';
if ($_POST){
    if ((new CUser)->Login($_POST['email'], $_POST['password'])) {
        if (!B2BMain::isClient($USER) && !B2BMain::isManager($USER)) {
            $error = 'Неверный логин и пароль';
        } else {
            LocalRedirect('https://' . SITE_SERVER_NAME . '/' . $_GET['path']);
        }
    } else {
        $error = 'Неверный логин и пароль';
    }
}
?>
    <main>
        <div class="auth-wrapper">
        <div id="auth-module" class="auth">
            <form method="post">
                <div class="auth__header">Вход</div>
                <span class="error-message"><?=$error?></span>
                <label for="email">
                    <input type="email" name="email" size="100px" placeholder="Email">
                    <span class="hint">Email</span>
                </label>
                <label for="password">
                    <input type="password" name="password" size="100px" placeholder="Пароль" id="password">
                    <span class="hint">Пароль</span>
                    <i class="fa fa-eye-slash password-hide" id="password-reveal"></i>
                </label>
                <button class="auth__submit" type="submit">Войти</button>
                <!--
                <div class="auth__details">
                    <span>Еще нет аккаунта? <a href="/register/">Зарегистрироваться</a></span>
                </div>
                <div class="auth__details">
                    <span>Входя в личный кабинет, Вы принимаете условия <a href="/politic/">политики</a> и
                    <a href="/agreement/">пользовательского соглашения</a> </span>
                </div>
                -->
            </form>
        </div>
        </div>