<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
require_once("includes/include.php");

use Bitrix\Main\UI\Extension;
Extension::load("ui.vue");
Extension::load('ui.bootstrap4');

?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<?

$controller = TOIR::controller('Test');
$controller->index()

?>



