<?php
require_once("includes/include.php");

$controller = TOIR::controller('CrashCreate');

$save = (int)$_REQUEST['save'];

$functionName = ($save ? "save" : 'index');

$controller->$functionName();

