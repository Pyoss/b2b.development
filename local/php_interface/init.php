<?php
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();
$eventManager->unRegisterEventHandler("main", "OnAdminIBlockElementEdit", "seo", "\\Bitrix\\Seo\\AdvTabEngine", "eventHandler");

$CLASSES_DIR = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/';

// Тестируем необходимые классы (список не окончательный)
require_once $CLASSES_DIR . 'main.php';


