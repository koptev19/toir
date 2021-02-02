<?php
require_once("includes/include.php");

$controller = TOIR::controller('CrashEdit');

if(!empty($_REQUEST['operations'])){
	$controller->operations();
}elseif(!empty($_REQUEST['done'])){
	$controller->crashDone();
}elseif(!empty($_REQUEST['histories'])){
	$controller->histories();
}elseif(!empty($_REQUEST['edit_description'])){
	$controller->editDescription();
}elseif(!empty($_REQUEST['save_description'])){
	$controller->saveDescription();
}elseif(!empty($_REQUEST['save_files'])){
	$controller->saveFiles();
}elseif(!empty($_REQUEST['edit_decision'])){
	$controller->editDecision();
}elseif(!empty($_REQUEST['save_decision'])){
	$controller->saveDecision();
}elseif(!empty($_REQUEST['save_decision_files'])){
	$controller->saveDecisionFiles();
}elseif(!empty($_REQUEST['delete_document_file'])){
	$controller->deleteFile();
}elseif(!empty($_REQUEST['delete_decision_file'])){
	$controller->deleteFile();
}elseif(!empty($_REQUEST['select_service_request'])){
	$controller->selectServiceRequest();
}elseif(!empty($_REQUEST['select_services'])){
	$controller->selectServices();
}

