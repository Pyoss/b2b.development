<?php

namespace DJ\B2B\Applications;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\ORM\Fields\Validators\Validator;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
Loc::loadMessages(__FILE__);


class ApplicationsTable extends DataManager {

    public static function getTableName()
    {
        return 'b2b_requests';
    }

    public static function getMap()
    {
        return array(
            new IntegerField('ID', array(
                'autocomplete' => true,
                'primary' => true
            )),// autocomplite с первичным ключом
            new StringField('FIO', array(
                'required' => true,
            )),
            new StringField('EMAIL', array(
                'required' => true,
            )),
            new StringField('PHONE', array(
                'required' => true,
            )),
            new StringField('INN', array(
                'required' => true,
            )),
            new StringField('OGRN', array(
                'required' => true,
            )),
            new StringField('COMPANY_TYPE', array(
                'required' => true,
            )),
            new StringField('COMPANY_NAME', array(
                'required' => true,
            )),
            new StringField('ACT_ADDRESS', array(
                'required' => true,
            )),
            new StringField('REG_ADDRESS', array(
                'required' => true,
            )),
            new StringField('ACCOUNT_NUM', array(
                'required' => true,
            )),
            new StringField('BIK', array(
                'required' => true,
            )),
            new StringField('KOR', array(
                'required' => true,
            )),
            new StringField('STATUS', array(
                'required' => true,
            )),
            new DatetimeField('UPDATED_AT',array(
                'required' => true)),//обязательное поле даты
            new DatetimeField('CREATED_AT',array(
                'required' => true)),//обязательное поле даты
        );
    }
}