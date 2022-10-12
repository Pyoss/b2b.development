<?php
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
?>
<div class="section">
    <h1 class="section__title">
        Текущие заявки
    </h1>
    <div class="app-list b2b-block">
        <?foreach ($arResult['APPLICATIONS'] as $application):
        if ($application['STATUS'] == 'PENDING') {
            $appClass = 'pending';
            $statusString = 'Новая заявка';
        } elseif ($application['STATUS'] == 'APPROVED') {
            $appClass = 'approved';
            $statusString = 'Заявка одобрена';
        } elseif ($application['STATUS'] == 'DENIED') {
            $appClass = 'denied';
            $statusString = 'Заявка отклонена';
        }
        ?>
        <div class="app-item <?= $appClass ?>">
            <div class="app-item__status"><?= $statusString ?></div>
            <div class="app-item__manager-name"><?= $application['FIO'] ?></div>
            <div class="app-item__company-name"><?= $application['COMPANY_NAME'] ?></div>
            <div class="app-item__button-container">
            <a class="app-item__details" href="/applications/<?= $application['ID'] ?>/">Подробнее</a>
            </div>
        </div>
        <?endforeach?>
    </div>
</div>
</div>