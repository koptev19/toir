<?php
define('DONT_CHECK_AUTH', true);

require_once("./includes/include.php");

$controller = TOIR::controller('AcceptItem');
$functionName = $_REQUEST['action'] ? $_REQUEST['action'] :"index" ;
$controller->$functionName();
