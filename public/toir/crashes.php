<?php
require_once("includes/include.php");

$controller = TOIR::controller('Crashes');

if(!empty($_REQUEST['add_operation'])) {
    $controller->addOperation();
}elseif(!empty($_REQUEST['get_operations'])){
	$controller->getOperations($_REQUEST['crashId']);
}elseif(!empty($_REQUEST['crashDone'])){
	$controller->crashDone($_REQUEST['crashDone']);
}elseif(!empty($_REQUEST['edit_description'])){
	$controller->editDescription();
} else {
    $controller->index();
}

