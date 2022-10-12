<?php
use Dadata\Api;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$CLASSES_DIR = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/';
require_once $CLASSES_DIR . 'Dadata/Api.php';
require_once $CLASSES_DIR . 'Dadata/Request.php';

$dadataApi = new Api();
if ($_GET['search_inn']){
    echo $dadataApi -> searchByInn((int)$_GET['search_inn']);
}
