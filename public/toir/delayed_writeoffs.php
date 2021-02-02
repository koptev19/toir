<?php
require_once("includes/include.php");

$controller = TOIR::controller('DelayedWriteoff');

if($_REQUEST['done']) {
    $functionName = "done";
} else {
    $functionName = "index";
}

$controller->$functionName();
