<?php
define('DONT_CHECK_AUTH', true);

require_once("includes/include.php");

$controller = TOIR::controller('Auth');

$functionName = $_REQUEST['action'] ?? 'index';

$controller->$functionName();
