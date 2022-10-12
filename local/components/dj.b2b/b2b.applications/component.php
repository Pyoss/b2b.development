<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


// В этой переменной будем накапливать значения истинных переменных
$arVariables = array();
$arDefaultUrlTemplates404 = array();
$arDefaultVariableAliases404 = array();

$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);


// Определим код шаблона, которому соответствует текущая запрошенная страница
$componentPage = CComponentEngine::ParseComponentPath(
    $arParams["SEF_FOLDER"],
    $arUrlTemplates,
    $arVariables
);


/*
 * Теперь на основании истинных переменных $arVariables можно определить, какую страницу
 * шаблона компонента нужно показать
 */
$componentPage = '';
if (isset($arVariables['APPLICATION_ID']) && intval($arVariables['APPLICATION_ID']) > 0)
    $componentPage = 'application_detail'; // элемент инфоблока по идентификатору
else
    $componentPage = 'application_list'; // главная страница компонента
$arResult['VARIABLES'] = $arVariables;
$arResult['FOLDER'] = '';

$this->IncludeComponentTemplate($componentPage);