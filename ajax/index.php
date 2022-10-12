<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
$arHide = array("HIDE_ICONS" => "Y");


use DJ\B2b\Bitrix1C\Api;
use DJ\B2B\Applications\B2BMain;

\Bitrix\Main\Loader::includeModule('dj.b2b');
$api1C = new Api();
$api = new B2BMain();

if ($_GET['create_company']) {
    $api1C->CreateCompany();
} elseif ($_GET['get_company']) {
    echo $api1C->GetCompany();
} elseif ($_GET['get_by_inn']) {
    $guid = $api1C->GetCompanyByInn($_GET['inn']);
    $found = (!strpos($guid, 'company_status'));
    if ($found) {
        echo $api1C->GetCompany($guid);
    } else {
        echo '{"error": true}';
    }
} elseif ($_GET['get_payment']) {
    header('Content-type: application/pdf');
    echo $api1C->GetPayment($_GET['get_payment']);
} elseif ($_GET['create_client']) {
    $filter = array("EMAIL" => $_GET['mail']);
    $rsUser = CUser::GetList(($by = "id"), ($order = "desc"), $filter);
    $found = $rsUser->Fetch()['ID'];
    if ($found){
        $r = $api->updateB2BUser($found, $_GET['inn'], $_GET['mail'], $_GET['name'], '', $_GET['phone']);

        (\Bitrix\Main\Mail\Event::send([
            "EVENT_NAME" => "TEST_EVENT",
            'MESSAGE_ID' => 92,
            "LID" => "bb",
            "C_FIELDS" => [
                'EMAIL' => $r['EMAIL'],
                'LOGIN' => $r['EMAIL'],
                'PASSWORD' => $r['PASSWORD'],
            ]
        ]));
        echo 'Клиент найден в базе данных. На почту ' . $r['EMAIL'] . ' отправлено письмо c новыми данными. (Логин: ' . $r['EMAIL'] .'; Пароль: ' . $r['PASSWORD'] . ')';
    } else {
        $r = $api->createB2BUser($_GET['inn'], $_GET['mail'], $_GET['name'], '', $_GET['phone']);

        (\Bitrix\Main\Mail\Event::send([
            "EVENT_NAME" => "TEST_EVENT",
            'MESSAGE_ID' => 92,
            "LID" => "bb",
            "C_FIELDS" => [
                'EMAIL' => $r['EMAIL'],
                'LOGIN' => $r['EMAIL'],
                'PASSWORD' => $r['PASSWORD'],
            ]
        ]));
        echo 'Клиент найден в базе данных. На почту ' . $r['EMAIL'] . ' отправлено письмо c новыми данными. (Логин: ' . $r['EMAIL'] .'; Пароль: ' . $r['PASSWORD'] . ')';
    }
}