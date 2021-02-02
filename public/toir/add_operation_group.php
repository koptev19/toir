<?php
require_once("includes/include.php");

$controller = TOIR::controller('AddOperationGroup');

$functionName = $_REQUEST['action'] ?? 'index';

$controller->$functionName();
