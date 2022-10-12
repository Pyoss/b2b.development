<?php

namespace DJ\B2B\Applications;

class B2BMain
{
    public static function isClient($USER): bool
    {
        $arUserGroups = $USER->GetUserGroupArray();
        $b2bGroupId = 0;
        $result = \Bitrix\Main\GroupTable::getList(array(
            'select'  => array('ID'),
            'filter'  => array('CODE'=>'b2b_clients')
        ));

        while ($arGroup = $result->fetch()) {
            $b2bGroupId = $arGroup['ID'];
        }
        print_r($b2bGroupId);
        print_r($arUserGroups);
        if (!$USER->IsAuthorized() || !in_array($b2bGroupId, $arUserGroups)) {
            return false;
        }
        return true;
    }

    public static function isManager($USER): bool
    {
        $arUserGroups = $USER->GetUserGroupArray();
        $b2bGroupId = 0;
        $result = \Bitrix\Main\GroupTable::getList(array(
            'select'  => array('ID'),
            'filter'  => array('CODE'=>'b2b_managers')
        ));

        while ($arGroup = $result->fetch()) {
            $b2bGroupId = $arGroup['ID'];
        }
        if (!$USER->IsAuthorized() || !in_array($b2bGroupId, $arUserGroups)) {
            return false;
        }
        return true;
    }


    public static function getCurrentClient($USER)
    {
        $user_id = $USER->GetID();
        \Bitrix\Main\Loader::IncludeModule("highloadblock");
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(5)->fetch();
        if (!$hlblock) {
            return false;
        }
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();
        $result = $entityDataClass::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "DESC"),
            "filter" => array("UF_USER_ID" => $user_id),
        ));
        while ($arRow = $result->Fetch()) {
            return $arRow;
        }
        return false;
    }

    private function generatePassword($length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function createClient($email, $first_name, $second_name, $phone): array
    {
        $password = $this->generatePassword();
        $user = new \CUser();
        $arFields = [
            'NAME' => $first_name,
            'LAST_NAME' => $second_name,
            'EMAIL' => $email,
            'LOGIN' => $email,
            'LID' => 'bb',
            'PASSWORD' => $password,
            'CONFIRM_PASSWORD' => $password,
            'PERSONAL_PHONE' => $phone
        ];

        $ID = $user->Add($arFields);
        if (intval($ID) > 0)
            return ['ID' => $ID, 'PASSWORD' => $password, 'EMAIL' => $email];
        else
            return ['ERROR' => $user->LAST_ERROR];
    }

    public function updateClient($id, $email, $first_name, $second_name, $phone): array
    {
        $password = $this->generatePassword();
        $user = new \CUser();
        $arFields = [
            'NAME' => $first_name,
            'LAST_NAME' => $second_name,
            'EMAIL' => $email,
            'LOGIN' => $email,
            'LID' => 'bb',
            'PASSWORD' => $password,
            'CONFIRM_PASSWORD' => $password,
            'PERSONAL_PHONE' => $phone
        ];

        $r = $user->Update($id, $arFields);
        if ($r)
            return ['ID' => $id, 'PASSWORD' => $password, 'EMAIL' => $email];
        else
            return ['ERROR' => $user->LAST_ERROR];
    }

    private function addClientToB2BGroup($ID)
    {
        $arGroups = \CUser::GetUserGroup($ID);
        $arGroups[] = 11;
        \CUser::SetUserGroup($ID, $arGroups);
    }

    private function fillClientHighload($CLIENT_ID, $CLIENT_GUID, $MANAGER_ID): void
    {

        \Bitrix\Main\Loader::IncludeModule("highloadblock");
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(5)->fetch();
        if (!$hlblock) {
            return;
        }
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();

        $rsData = $entityDataClass::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_USER_ID"=>$CLIENT_ID)  // Задаем параметры фильтра выборки
        ));
        while($arData = $rsData->Fetch()){
            $entityDataClass::Delete($arData['ID']);
        }

        $result = $entityDataClass::add(
            array('UF_COMPANY_GUID' => $CLIENT_GUID,
                'UF_USER_ID' => $CLIENT_ID,
                'UF_MANAGER_ID' => $MANAGER_ID));
    }

    public function updateB2BUser($user_id, $inn, $email, $first_name=false, $last_name=false, $phone=false)
    {
        $api = new \DJ\B2B\Bitrix1C\Api;
        $guid = $api->GetCompanyByInn($inn);
        $company = $api -> GetCompany($guid);
        if ($company['error'] == false){
            return $company['error'];
        }

        $rsUser = $this->updateClient($user_id, $email?? $company['email'], $first_name ?? $company['name'], $last_name, $phone??$company['phone'][0]);

        if (!$rsUser['ERROR']) {
            $this->addClientToB2BGroup($user_id);

            $this->fillClientHighload($user_id, $guid, ($api -> managerArray[$company['manager']] ?? $api -> managerArray['default']));

            \Bitrix\Sender\Subscription::add(
                $email, array('MAILING_ID' => 4, 'CONTACT_ID' => $user_id));
        }
        return $rsUser;
    }

    public function createB2BUser($inn, $email, $first_name=false, $last_name=false, $phone=false)
    {
        $api = new \DJ\B2B\Bitrix1C\Api;
        $guid = $api->GetCompanyByInn($inn);
        $company = $api -> GetCompany($guid);
        if ($company['error'] == false){
            return $company['error'];
        }
        $rsUser = $this->createClient($email?? $company['email'], $first_name ?? $company['name'], $last_name, $phone??$company['phone'][0]);
        if (!$rsUser['ERROR']) {
            $this->addClientToB2BGroup($rsUser['ID']);

            $this->fillClientHighload($rsUser['ID'], $guid, ($api -> managerArray[$company['manager']] ?? $api -> managerArray['default']));

            \Bitrix\Sender\Subscription::add(
                $email, array('MAILING_ID' => 4, 'CONTACT_ID' => $rsUser['ID']));
        }
        return $rsUser;
    }
}