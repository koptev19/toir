<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$this->component('select_equipment_2021');
?>

<div id="vue-application"></div>

<script>
    BX.Vue.create({
        el: '#vue-application',
        template: '<select-equipment/>'
    });
</script>

<?php $this->showFooter();
