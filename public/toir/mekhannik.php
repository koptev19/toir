<?php
require_once("includes/include.php");

$controller = TOIR::controller('Mekhannik');

$step = (int)$_REQUEST['step'];
$step = max($step, 1);
$step = min($step, 5);

$functionName = "step".$step;

$controller->$functionName();

