<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
require_once("includes/include.php");

TOIR::controller('WorkersAutocomplete');
if($_REQUEST['term']){
	ToirWorkersAutocompleteController::getWorkers($_REQUEST['term']);
}

