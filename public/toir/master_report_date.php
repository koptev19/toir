<?php
require_once("includes/include.php");

$controller = TOIR::controller('MasterReportDate');

if($_REQUEST['delete_in_session']) {
    $functionName = 'deleteInSession';
} elseif($_REQUEST['update_field']) {
    $functionName = 'updateField';
} else {
    $step = (int)$_REQUEST['step'];
    $step = max($step, 1);
    $step = min($step, 5);

    $functionName = "step".$step;
}

$controller->$functionName();

