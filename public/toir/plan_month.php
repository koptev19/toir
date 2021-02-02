<?php
require_once("includes/include.php");

$controller = TOIR::controller('PlanMonth');

$step = (int)$_REQUEST['step'];
$step = max($step, 1);
$step = min($step, 5);

$save = (int)$_REQUEST['save'];

$functionName = $_REQUEST['action'] ? $_REQUEST['action'] : "step".$step . ($save ? "_save" : '');

$controller->$functionName();
