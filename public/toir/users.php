<?php
require_once("includes/include.php");

$controller = TOIR::controller('User');

$action = $_REQUEST['ACTION'] ?? "index";

$functionName = $action;

$controller->$functionName();

