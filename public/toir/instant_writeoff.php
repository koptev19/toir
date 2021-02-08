<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
global $DB;
global $USER;

use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');

require_once("includes/include.php");

$controller = TOIR::controller('InstantWriteoff');

$functionName = $_REQUEST['action'] ?? 'index';

$controller->$functionName();
