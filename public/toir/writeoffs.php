<?php
require_once("includes/include.php");

$controller = TOIR::controller('Writeoffs');

$functionname = $_REQUEST['action'] ?? 'index';

$controller->$functionname();

