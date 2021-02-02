<?php
require_once("includes/include.php");

$controller = TOIR::controller('AddHistoryGroup');

$functionName = $_REQUEST['action'] ?? 'index';

$controller->$functionName();
