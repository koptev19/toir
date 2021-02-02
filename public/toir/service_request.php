<?php
require_once("includes/include.php");

$controller = TOIR::controller('ServiceRequest');

if($_REQUEST['service_request_id']) {
    $controller->operationsByServiceRequest();
} else {
    $controller->index();
}

