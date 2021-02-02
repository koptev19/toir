<?php
require_once("includes/include.php");


$controller = TOIR::controller('Ajax');

$action = $_REQUEST['action'];

$functionName = $action;

$controller->$functionName();

