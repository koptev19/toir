<?php

//define('TOIR_PATH', '/public/toir/');
define('TOIR_PATH', '/toir/');


session_start();

/*config*/
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/config.php");

require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/functions.php");

/* TOIR */
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Toir.php");

/* Core */
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Core/ToirController.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Core/MysqlConnecter.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Core/ToirModelBuilder.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Core/ToirModel.php");

/* Models */
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Accept.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/AcceptItem.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Equipment.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Crash.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/DateProcess.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/DelayedWriteoff.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/File.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/History.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Line.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Operation.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Plan.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Registry.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Receiving.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Service.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/ServiceRequest.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Settings.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Stop.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/UserToir.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Work.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Worker.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Workshop.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Downtime.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Worktime.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Models/Writeoff.php");

/* Services */
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/AnaliticService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/CrashService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/DateProcessService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/FileService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/HistoryService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/OperationService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/PlanService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/PushService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/ReceivingService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/ServiceRequestService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/StopService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/TaskService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/UserService.php");


if (!defined('DONT_CHECK_AUTH') || !DONT_CHECK_AUTH) {
    if(empty(UserToir::current())) {
        header("Location: auth.php");
    }
}
