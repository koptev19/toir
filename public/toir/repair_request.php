<?php
require_once("includes/include.php");

$controller = TOIR::controller('RepairRequest');

if($_REQUEST['save']) {
    $controller->save();
} else {
    $controller->index();
}

