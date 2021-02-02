<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
global $DB;
global $USER;

use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');

require_once("includes/include.php");

$controller = TOIR::controller('AddOperation');

if($_REQUEST['delete']) {
    $functionName = 'delete';
} else {
    $step = (int)$_REQUEST['step'];
    $step = max($step, 1);
    $save = (int)$_REQUEST['save'] ? "_save" : '';

    $functionName = "step".$step.$save;
}

$controller->$functionName();
