<?php
require_once("includes/include.php");

$controller = TOIR::controller('PlanDateChecking');

if($_REQUEST['approve']) {
    $functionName = 'approve';
} elseif($_REQUEST['reject']) {
    $functionName = 'reject';
} elseif($_REQUEST['cancel_stage']) {
    $functionName = 'cancel_stage';
} else {
    $functionName = 'index';
}
$controller->$functionName();

