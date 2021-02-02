<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $DB;
global $USER;

use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');

require_once($_SERVER["DOCUMENT_ROOT"]."/toir/includes/include.php");
$toir=new ToirController();
$toir->view('components/select_equipment', ["multiply"=>true]);
?>


<table>
<tr><td><input type=hidden name='equipment[]' class='equipment-select-input'>
<tr><td><input type=hidden name='equipment[]' class='equipment-select-input'>
<tr><td><input type=hidden value="35" name='equipment[]' class='equipment-select-input'>
<tr><td><input type=hidden id="newInput" value="20" name='equipmentasd[]' class=''>
<tr><td><input type=hidden id="newInput1" value="" name='equipmentaaa[]' class=''>
</table>

<script>
$(document).ready(function() {
makeEquipmentHref($("#newInput"));
makeEquipmentHref($("#newInput1"));
});
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>