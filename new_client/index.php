<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заявки");
use DJ\B2B\Applications\B2BMain;

if (!B2BMain::isManager($USER)){
    LocalRedirect('/');
}
?>
<?php
$APPLICATION->IncludeComponent(
    "dj.b2b:b2b.new_client",
    "",
    Array(
    )
);
?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>