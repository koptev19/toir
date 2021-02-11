<?php
require_once("includes/include.php");

$controller = TOIR::controller('InstantWriteoff');

$functionName = $_REQUEST['action'] ?? 'index';

$controller->$functionName();
