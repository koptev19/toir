<?php
require_once("includes/include.php");

$controller = TOIR::controller('LogReceiving');
$functionName = $_REQUEST['action'] ?? 'index';
$controller->$functionName();

