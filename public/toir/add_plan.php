<?php
require_once("includes/include.php");

$controller = TOIR::controller('AddPlan');

if($_REQUEST['action']){
	$action = $_REQUEST['action'];	
	$controller->$action();
} elseif(!empty($_REQUEST['pushToLeft'])) {
    $controller->pushToLeft();    
} elseif(!empty($_REQUEST['pushToRight'])) {
    $controller->pushToRight();    
} else {
    $step = (int)$_REQUEST['step'];
    $step = max($step, 1);
    $save = (int)$_REQUEST['save'] ? "_save" : '';

    $functionName = "step".$step.$save;

    $controller->$functionName();
}
