<?php

global $INTRANET_TOOLBAR, $APPLICATION;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle("Журнал плановых операций");
$APPLICATION->AddHeadScript(TOIR_PATH . "scripts/equipment.js");
$APPLICATION->AddHeadScript("https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js");
$APPLICATION->SetAdditionalCSS("https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css", true);
$APPLICATION->SetAdditionalCSS(TOIR_PATH . "styles/style.css", true);

