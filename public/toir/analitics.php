<?php
require_once("includes/include.php");

$controller = TOIR::controller('Analitics');

if($_REQUEST['table4Equipment']) {
    $controller->table4Equipment();
} else {
    $controller->index();
}

