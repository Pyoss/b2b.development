<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use DJ\B2B\Applications\ApplicationsTable;


Loc::loadMessages(__FILE__);

class dj_b2b extends CModule
{

    public function __construct()
    {
        if (is_file(__DIR__ . '/version.php')) {
            $arModuleVersion = array();
            include_once(__DIR__ . '/version.php');
            $this->MODULE_ID = 'dj.b2b';
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME = Loc::getMessage('NAME');
            $this->MODULE_DESCRIPTION = Loc::getMessage('DESCRIPTION');
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('FILE_NOT_FOUND') . ' version.php'
            );
        }
    }

    public function doInstall()
    {

        global $APPLICATION;

        // мы используем функционал нового ядра D7 — поддерживает ли его система?
        if (CheckVersion(ModuleManager::getVersion('main'), '14.00.00')) {

            // регистрируем модуль в системе
            ModuleManager::registerModule($this->MODULE_ID);
            // создаем таблицы БД, необходимые для работы модуля
            $this->installDB();
            // регистрируем обработчики событий
            $this->installEvents();
        } else {
            CAdminMessage::showMessage(
                Loc::getMessage('INSTALL_ERROR')
            );
            return;
        }

        $APPLICATION->includeAdminFile(
            Loc::getMessage('INSTALL_TITLE') . ' «' . Loc::getMessage('NAME') . '»',
            __DIR__ . '/step.php'
        );
    }

    public function installFiles()
    {
        return;
    }

    public function installDB()
    {
        var_dump(Loader::includeModule($this->MODULE_ID));
        ApplicationsTable::getEntity()->createDbTable();
    }

    public function installEvents()
    {
        return;
    }

    public function doUninstall()
    {

        global $APPLICATION;

        $this->uninstallFiles();
        $this->uninstallDB();
        $this->uninstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->includeAdminFile(
            Loc::getMessage('UNINSTALL_TITLE') . ' «' . Loc::getMessage('NAME') . '»',
            __DIR__ . '/unstep.php'
        );

    }

    public function uninstallFiles()
    {
        // удаляем настройки нашего модуля
        Option::delete($this->MODULE_ID);
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            if (Application::getConnection()->isTableExists('b2b_requests')) {
                $connection = Application::getInstance()->getConnection();
                $connection->dropTable(ApplicationsTable::getTableName());
            }
        }
    }

    public function uninstallEvents()
    {
        return;
    }

}