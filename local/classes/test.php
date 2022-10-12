<?php


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define('BX_NO_ACCELERATOR_RESET', true);
define('CHK_EVENT', true);
define('BX_WITH_ON_AFTER_EPILOG', true);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
@set_time_limit(0);
@ignore_user_abort(true);
CModule::IncludeModule('subscribe');

/*
$filter = Array
(
    "GROUPS_ID"=> Array(11) // ID gruppi
);
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
while($arItem = $rsUsers->GetNext())
{
    \Bitrix\Sender\Subscription::add(
        $arItem['EMAIL'], array('MAILING_ID' => 4, 'CONTACT_ID' => $arItem['ID']));

}

*/
CModule::IncludeModule('dj.b2b');
use DJ\B2B\Applications\B2BMain;$user = new \CUser();
$api = new B2BMain();    $arFields = [
    'NAME' => 'Игорь',
    'LAST_NAME' => 'Кирия',
    'EMAIL' => 'igork@dobriy-jar.ru',
    'LOGIN' => 'igork@dobriy-jar.ru',
    'LID' => 'bb',
    'PASSWORD' => '334ab7f2c',
    'CONFIRM_PASSWORD' => '334ab7f2c',
];

$r = $user->Update(1, $arFields);
print_r($r);
/*
$res  = $api -> createB2BUser('231224704031', 'zakup@samogondar.ru', 'zakup@samogondar.ru');
print_r($res);
phpinfo();
var_dump(\Bitrix\Main\Mail\Event::send([
    "EVENT_NAME" => "TEST_EVENT",
    'MESSAGE_ID' => 92,
    "LID" => "bb",
    "C_FIELDS" => [
        'EMAIL' => 'vsedlyabaniopt@mail.ru',
        'LOGIN' => 'vsedlyabaniopt@mail.ru',
        'PASSWORD' => 'kcXoJRtC9N',
    ]
]));


$row = 1;
if (($handle = fopen("baza-true.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        $row++;
        $name = $data[0];
        $inn = $data[2];
        $mail = $data[5];
        $api = new B2BMain();
        if (!$name || !$mail){
            continue;
        }
        $res  = $api -> createB2BUser($inn, explode(', ', $mail)[0], $name);
        print_r($res);
        echo '<br>';
        $er = \Bitrix\Main\Mail\Event::send([
            "EVENT_NAME" => "TEST_EVENT",
            'MESSAGE_ID' => 92,
            "LID" => "bb",
            "C_FIELDS" => [
                'EMAIL' => $res['EMAIL'],
                'LOGIN' => $res['EMAIL'],
                'PASSWORD' => $res['PASSWORD'],
            ]
        ]);
        print_r($er -> isSuccess());
        print_r($er -> getErrorMessages());

    }
    fclose($handle);
}
*/
?>