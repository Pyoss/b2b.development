<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var $arResult
 * @var $arParams
 */

if ($_GET['logout'] == 'true'){
    global $USER;
    $USER -> Logout();
    LocalRedirect('/auth/');
}