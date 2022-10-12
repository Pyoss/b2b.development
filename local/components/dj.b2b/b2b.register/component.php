<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

CModule::IncludeModule('dj.b2b');

use DJ\B2B\Applications\ApplicationsTable;
use Bitrix\Main\Localization\Loc;

function GetPostErrors($post_data)
{
    $arErrors = [];
    if (strlen($post_data['num']) !== 9) {
        $arErrors[] = Loc::getMessage('num');
    }
    return $arErrors;
}

if ($_POST) {
    $POST_ERRORS = GetPostErrors($_POST);
    if ($POST_ERRORS) {
        print_r($POST_ERRORS);
    }
}

if (!$_POST || $POST_ERRORS) {
    $this->includeComponentTemplate();
} else {
    ApplicationsTable::add(
        array(
            'FIO' => $_POST['FIO'],
            'EMAIL' => $_POST['email'],
            'PHONE' => $_POST['tel'],
            'INN' => $_POST['inn'],
            'OGRN' => $_POST['ogrn'],
            'COMPANY_TYPE' => $_POST['company-type'],
            'COMPANY_NAME' => $_POST['company-name'],
            'ACT_ADDRESS' => $_POST['act-address'],
            'REG_ADDRESS' => $_POST['reg-address'],
            'ACCOUNT_NUM' => $_POST['num'],
            'BIK' => $_POST['bik'],
            'KOR' => $_POST['kor'],
            'STATUS' => 'PENDING',
            'CREATED_AT' => new \Bitrix\Main\Type\DateTime(),
            'UPDATED_AT' => new \Bitrix\Main\Type\DateTime(),));

    $this->includeComponentTemplate('confirm');
}