<?php 
	 if($_SESSION['add_operation_errors'] && is_array($_SESSION['add_operation_errors']) && count($_SESSION['add_operation_errors'])){
		echo "<ul style='background:#f6caca'>";
		foreach ($_SESSION['add_operation_errors'] as $error){
			echo "<li>".$error;
		}
		echo "</ul>";
	  }

    $oneService = count($services) == 1 ? reset($services) : null;
?>

<h1 class='text-center mb-5'>Шаг 1 - внесение данных</h1>
<form  method="post" action="">
<input type="hidden" name="save" value="1">
<input type="hidden" name="step" value="1">

<div class="mb-3 row">
    <label class='col-2 col-form-label font-weight-bold'>Оборудование</label>
    <div class="col-10">
        <?php
            $this->component('select_equipment_2021', [
                'selectedBranch' => $this->workshop->ID,
                'equipment' => $equipment,
            ]);
        ?>
    </div>
</div>

<div class="mb-3 row">
    <label class='col-2 col-form-label'>Служба</label>
    <div class="col-10">
    <?php if(count($services) > 1) { ?>
        <select name="SERVICE" required class="custom-select form-select" id='SERVICE'>
            <?php foreach($services as $service) { ?>
                <option value="<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></option>
            <?php } ?>
        </select>
        <?php } else { ?>
            <input type="hidden" name="SERVICE" value="<?php echo $oneService->ID; ?>">
            <input type="text" value="<?php echo $oneService->NAME; ?>" readonly class="form-control">
        <?php } ?>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Название регламентной операции</div>
    <div class="col-10">
        <input type="text" name="NAME" class="form-control" required>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Тип операции</div>
    <div class="col-10">
        <select name="TYPE_OPERATION" required class="custom-select form-select">
            <option value="0" disabled selected hidden>Выберите</option>
            <?php foreach(Operation::getEnumList('TYPE_OPERATION') as $typeId => $typeName) { ?>
                <option value="<?php echo $typeId; ?>"><?php echo $typeName; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Причина возникновения</div>
    <div class="col-10">
        <?php if($reason) { ?>
            <input type="hidden" name="REASON" value="<?php echo $reason?>">
            <input type="text" value="<?php echo Operation::verbalReason($reason); ?>" class="form-control" readonly>
        <?php } else { ?>
            <select name="REASON" required class="custom-select form-select">
                <option value="0" disabled selected hidden>Выберите</option>
                <?php foreach(Operation::reasons() as $reasonId => $reasonName) { ?>
                    <option value="<?php echo $reasonId; ?>"><?php echo $reasonName; ?></option>
                <?php } ?>
            </select>
        <?php } ?>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Рекомендации</div>
    <div class="col-10">
        <input type="text" name="RECOMMENDATION" class="form-control">
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Ответственный исполнитель</div>
    <div class="col-10"><input type="text" name="OWNER" class="form-control" required></div>
</div>

<div class="row mb-5"  id="dateselector" <?php if(!$this->date) {?> style="display:none" <?php } ?>>
    <div class='col-2'>Планируемая Дата выполнения</div>
    <div class="col-10">
    <?php if($this->date) {?>
        <input type="text" readonly name="PLANNED_DATE" value="<?php echo $this->date; ?>" class='form-control'> 
    <? } else {
        $this->view('components/select_date', [
            'lineId' => 0,
            'serviceId' => reset($services)->ID,
            'monthsCount' => 3,
            'fieldName' => 'PLANNED_DATE',
            'createStopDate' => [1],
        ]);
    }
    ?>
    </div>
</div>

<input value="Добавить" type="submit" class='btn btn-primary'>
</form>

<?php if(!$this->date) {?> 
<script>

$(function() {
    $('#line').on('change', function() {
        if($('#line').val()) {
            lineId = $('#line').val();
            showStopDateLine();
        }
    });

    $('#SERVICE').on('change', function() {
        serviceId = $('#SERVICE').val();
        showStopDateLine();
    });
});

</script>
<?php } ?>



