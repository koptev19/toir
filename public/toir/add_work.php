<?php
require_once("includes/include.php");

$controller = TOIR::controller('AddWork');

$functionName = $_REQUEST['action'] ?? 'index';

$controller->$functionName();
