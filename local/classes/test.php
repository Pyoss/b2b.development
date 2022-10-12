<?php

use Bitrix1C\Api;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$CLASSES_DIR = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/';
require_once $CLASSES_DIR . 'Api1C/Api.php';
require_once $CLASSES_DIR . 'Api1C/Request.php';


$api1C = new Api();

if ($_GET['create_company']) {
    $api1C->CreateCompany();
} elseif ($_GET['get_company']) {
    echo $api1C->GetCompany();
} elseif ($_GET['create_order']) {
    $api1C->CreateOrder();
} elseif ($_GET['get_payment']) {
    header('Content-type: application/pdf');
    echo $api1C->GetPayment($_GET['get_payment']);
}
$api1C->showLog();
