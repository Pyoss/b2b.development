<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @var $arResult
 * @var $arParams
 */
?>

<section class="section">
    <h1 class="section__title">
        Счета
    </h1>
    <div class="b2b-block payments">
        <table>
            <thead>
            <tr class="b2b-table__row--header">
                <?
                foreach ($arResult['GRID']['HEAD'] as $CLASS => $HEADER) {
                    ?>
                    <td class="b2b-table__cell <?= $CLASS ?>"><?= $HEADER ?></td>
                    <?
                } ?>
            </tr>
            </thead>
            <tbody>
            <?
            if (!$arResult['GRID']['ROWS']){
                ?>У вас нет выписанных счетов<?
            }
            foreach ($arResult['GRID']['ROWS'] as $payment):
                ?><tr><?
                foreach ($payment as $class => $row):
                    switch ($class):
                        case 'payment_guid':
                            ?>
                            <td class="b2b-table__cell <?= $class ?>"><a
                                        target='_blank'
                                        href="/local/classes/test.php?get_payment=<?= $row ?>">
                                    <span>Скачать</span></a></td>
                            <? break;
                        default:
                            ?>
                            <td class="b2b-table__cell <?= $class ?>"><?= $row ?></td>
                            <? break;
                    endswitch;
                endforeach;
            ?></tr><?
            endforeach;
            ?>

            </tbody>
        </table>
    </div>

</section>
