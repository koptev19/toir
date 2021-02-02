<?php

require_once("includes/include.php");

$controller = TOIR::controller('MasterPlanDate');

if($_REQUEST['delete_in_session']) {
    $functionName = 'deleteInSession';
} elseif($_REQUEST['update_field']) {
    $functionName = 'updateField';
} else {
    $mode = $_REQUEST['mode'] ?? 'plan';

    $step = (int)$_REQUEST['step'];
    $step = max($step, 1);

    $save = (int)$_REQUEST['save'];

    $functionName = $mode . "_step".$step . ($save ? "_save" : '');
}

$controller->$functionName();

