<?php
require_once("includes/include.php");

$controller = TOIR::controller('WorkPlanned');
$action = $_REQUEST['action'] ?? "index";
$controller->$action();

