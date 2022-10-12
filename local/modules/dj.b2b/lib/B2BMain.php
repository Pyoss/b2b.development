<?php

namespace DJ\B2B\Applications;

class B2BMain
{
    public static function isClient($USER): bool
    {

        $arUserGroups = $USER -> GetUserGroupArray();
        $b2bGroupId = 10;
        if (!$USER -> IsAuthorized() || !in_array($b2bGroupId, $arUserGroups)){
            return false;
        }
        return true;
    }

    public static function isManager($USER): bool
    {
        $arUserGroups = $USER -> GetUserGroupArray();
        $b2bGroupId = 11;
        if (!$USER -> IsAuthorized() || !in_array($b2bGroupId, $arUserGroups)){
            return false;
        }
        return true;
    }

    public static function getCurrentClient($USER){
        $user_id = $USER->GetID();
        \Bitrix\Main\Loader::IncludeModule("highloadblock");
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(5)->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();
        $result = $entityDataClass::getList(array(
            "select" => array("*"),
            "order" => array("ID"=>"DESC"),
            "filter" => Array("UF_USER_ID"=>$user_id),
        ));

        while ($arRow = $result->Fetch())
        {
            return $arRow;
        }
        return false;
    }
}