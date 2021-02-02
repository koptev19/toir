<?php 
	 if($_SESSION['add_plan_errors'] && is_array($_SESSION['add_plan_errors']) && count($_SESSION['add_plan_errors'])){
		echo "<ul style='background:#f6caca'>";
		foreach ($_SESSION['add_plan_errors'] as $error){
			echo "<li>".$error;
		}
		echo "</ul>";
	  }
	  
?>

<h1 class='text-center mb-5'>Шаг 1 - внесение данных</h1>
<form  method="post" action="<?php echo ($_REQUEST['action'])? "add_plan.php":"" ?> ">
<input type="hidden" name="save" value="1">
<input type="hidden" name="step" value="1">

<div class="mb-3 row">
    <label class='col-2 col-form-label font-weight-bold'>Оборудование</label>
    <div class="col-10">
    <?php if(isset($work)) {?> 
		<div class="border rounded p-2" style="background-color:#e9ecef" >
			<?php echo $work->equipment()->getPath(" / ",false,true)." / ".$work->equipment()->NAME; ?>
			<input type="hidden" name="workshop" value="<?php echo $work->WORKSHOP_ID ?>">
			<input type="hidden" name="line" value="<?php echo $work->LINE_ID ?>">
			<input type="hidden" name="equipment" value="<?php echo $work->EQUIPMENT_ID ?>">
			<script>
				$(function() {
				lineId = <?php echo $work->LINE_ID ?>;
	            showStopDateLine();
				});
			</script>
		</div>	
	<?php }else{ 
            $this->view('components/select_equipment', [
                'selectedBranch' => $this->workshop->ID,
                'equipment' => $equipment,
            ]);
	}?>
    </div>
</div>

    <div class="mb-3 row">
        <label class='col-2 col-form-label'>Служба</label>
        <div class="col-10">
        <?php if(count($services) > 1) { ?>
            <select name="SERVICE_ID" required class="custom-select form-select" id="SERVICE_ID">
                <?php foreach($services as $service) { ?>
                    <option value="<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></option>
                <?php } ?>
            </select>
        <?php } else { 
            $service = reset($services); ?>
            <input type="hidden" name="SERVICE_ID" value="<?php echo $service->ID; ?>">
            <input type="text" value="<?php echo $service->NAME; ?>" readonly class="form-control">
        <?php } ?>
        </div>
    </div>

<div class="mb-3 row">
    <label class='col-2 col-form-label'>Вид ТО</label>
    <div class="col-10">
        <select name="TYPE_TO" required class="custom-select form-select">
            <option value="0" disabled selected hidden>Выберите</option>
            <?php foreach(Plan::getTypesTo() as $typeId => $typeName) { ?>
                <option value="<?php echo $typeId; ?>"><?php echo $typeName; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Периодичность</div>
    <div class="col-10">
        <input type="number" min="1" name="PERIODICITY" class="form-control" required>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Название регламентной операции</div>
    <div class="col-10">
        <input value="<?php echo (isset($work)? $work->NAME:"" ) ?>"type="text" name="NAME" class="form-control" <?php echo (isset($work)? " readonly ":"" ) ?> required>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Тип операции</div>
    <div class="col-10">
        
		<?php if(isset($work)) { 
			$type = Operation::getTypes();
			?>
			<input type="hidden" name="TYPE_OPERATION" value="<?php echo $work->TYPE; ?>">
            <input type="text" value="<?php echo $type[$work->TYPE]; ?>" readonly class="form-control">
        <?php } else { ?>
        <select name="TYPE_OPERATION" required class="custom-select form-select">
            <option value="0" disabled selected hidden>Выберите</option>
            <?php foreach(Operation::getTypes() as $typeId => $typeName) { ?>
                <option value="<?php echo $typeId; ?>"><?php echo $typeName; ?></option>
            <?php } ?>
        </select>
        <?php } ?>

		
		
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
        <input value="<?php echo (isset($work)? $work->RECOMMENDATION:"" ) ?>" type="text" name="RECOMMENDATION" <?php echo (isset($work)? " readonly ":"" ) ?> class="form-control">
    </div>
</div>

<div class="row mb-5"  id="dateselector" <?php if(!$this->date) {?> style="display:none" <?php } ?>>
    <div class='col-2'>Планируемая Дата выполнения</div>
    <div class="col-10">
    <?php if($this->date) {?>
        <input type="text" readonly name="PLANNED_DATE" value="<?php echo $this->date; ?>" class='form-control'> 
    <?php } else {
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

    $('#SERVICE_ID').on('change', function() {
        serviceId = $('#SERVICE_ID').val();
        showStopDateLine();
    });
});

</script>
<?php } ?>


