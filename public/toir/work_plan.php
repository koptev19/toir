<?php
require_once("includes/include.php");

$controller = TOIR::controller('WorkPlan');

$functionName = $_REQUEST['action'] ?? 'index';

$controller->$functionName();

