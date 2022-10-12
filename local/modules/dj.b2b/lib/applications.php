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
            new StringField('POST_DATA', array(
                'required' => true,
                'title' => Loc::getMessage('B2B_APPLICATION'),
                'default_value' => function () {
                    return '[]';
                },
                'validation' => function () {
                    return array(
                        new Validator\Length(null, 255),
                    );
                },
            )),//обязательная строка с default значением и длиной не более 255 символов
            new DatetimeField('UPDATED_AT',array(
                'required' => true)),//обязательное поле даты
            new DatetimeField('CREATED_AT',array(
                'required' => true)),//обязательное поле даты
        );
    }
}