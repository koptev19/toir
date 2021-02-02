<?php
require_once("./includes/include.php");

$controller = TOIR::controller('Accept');
$functionName = $_REQUEST['action'] ? $_REQUEST['action'] :"index" ;
$controller->$functionName();
