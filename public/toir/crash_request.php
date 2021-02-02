<?php
require_once("includes/include.php");

$controller = TOIR::controller('CrashRequest');

if($_REQUEST['save']) {
    $controller->save();
} else {
    $controller->index();
}

