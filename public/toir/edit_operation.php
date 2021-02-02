<?php
require_once("includes/include.php");

$controller = TOIR::controller('EditOperation');


if(!empty($_REQUEST['pushToLeft'])) {
    $controller->pushToLeft();    
} elseif(!empty($_REQUEST['pushToRight'])) {
    $controller->pushToRight();    
} else {
$step = (int)$_REQUEST['step'];
$step = max($step, 1);
$step = min($step, 3);

$functionName = "step".$step;

$controller->$functionName();
}
 

