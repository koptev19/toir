<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
global $DB;
global $USER;

require_once("includes/include.php");


$controller = TOIR::controller('Table3');
$controller->noComments();


