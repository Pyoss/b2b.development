<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 * @var $component
 * @var $APPLICATION
 */
?>

<div class="section">
    <?php
    $APPLICATION->IncludeComponent(
        "dj.b2b:b2b.product_list",
        "basket_product_list",
        array(
            "COMPONENT_TEMPLATE" => "basket_product_list",
            "PROPERTY_ID" => array(
                0 => "9",
            ),
            'ORDER_ID' => $arParams['ORDER_ID']
        ),
        $component
    );
    ?>
    <?php
    /*
    DJMain::displayString($arResult);
    DJMain::displayString(\Bitrix\Main\Context::getCurrent()->getRequest());
    */?>
    <h3 class="b2b-block-title">Оформление заказа</h3>
    <form name="order_form" method="post" action="/order/" class="order b2b-block">
        <div class="order__prop radio">
            <span class="order__prop-title">Службы доставки</span>
            <div class="order__radio-wrapper">
            <?php foreach ($arResult['DELIVERY_SERVICES'] as $delivery): ?>
                <input type="radio" id="<?=$delivery['ID']?>" name="delivery" value="<?=$delivery['CODE']?>" required>
                <label for="<?=$delivery['CODE']?>"><?=$delivery['NAME']?></label>
            <br>

            <?php endforeach; ?>   </div>
        </div>
        <?php foreach ($arResult['PROPS'] as $prop):
            if ($prop['CODE'] === 'order_guid' || $prop['NAME'] === 'Рассчитанный тариф СДЭК'){
                continue;
            }
            switch ($prop['TYPE']):
                case 'ENUM':
                    ?>
                    <div class="order__prop select">
                        <label class="order__prop-title" for="<?= $prop['CODE'] ?>"><?= $prop['NAME'] ?></label>
                            <select name="<?= $prop['CODE'] ?>" required>
                                <?php foreach ($prop['VALUES'] as $value): ?>
                                    <option value="<?= $value['NAME'] ?>"><?= $value['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                    <?php break;
                case 'DATE':
                    ?>
                    <div class="order__prop date" style="display: none">
                        <label class="order__prop-title" for="<?= $prop['CODE'] ?>"><?= $prop['NAME'] ?></label>
                            <input required type="text" value="<?=date("d.m.Y")?>" name="<?= $prop['CODE'] ?>"
                                   onclick="BX.calendar({node: this, field: this, bTime: false});">

                    </div>
                    <?php
                    break;
                case 'STRING':
                    ?>
                    <div class="order__prop textarea">
                        <label class="order__prop-title" for="<?= $prop['CODE'] ?>"><?= $prop['NAME'] ?></label>
                            <textarea style="resize: none;" name="<?= $prop['CODE'] ?>"></textarea>

                    </div>
                    <?php
                    break;
                default:
                    ?>

                    <?php break; ?>
                <?php endswitch;
        endforeach; ?>
        <input type="submit" name="order" value="Оформить заказ">
    </form>
</div>