<?php 
if($_SESSION['add_history_errors'] && is_array($_SESSION['add_history_errors']) && count($_SESSION['add_history_errors'])){
    foreach ($_SESSION['add_history_errors'] as $error){
        echo "<div class='alert alert-danger mt-4' role='alert'>" . $error . "</div>";
    }
}
?>

<h1 class='text-center mb-5'>Шаг 1 - внесение данных</h1>
<form  method="post" action="">
<input type="hidden" name="save" value="1">
<input type="hidden" name="step" value="1">

<div class="mb-3 row">
    <label class='col-2 col-form-label font-weight-bold'>Оборудование</label>
    <div class="col-10">
        <?php
        if($serviceRequest) {
            $equipment = $serviceRequest->equipment(); ?>
            <div class="p-2"><?php echo $equipment->path(); ?></div>
            <input type="hidden" name='workshop' value='<?php echo $equipment->WORKSHOP_ID; ?>'>
            <input type="hidden" name='line' value='<?php echo $equipment->LINE_ID; ?>'>
            <input type="hidden" name='equipment' value='<?php echo $equipment->ID; ?>'>
        <?php } else {
            $this->view('components/select_equipment', [
            ]);
        }
        ?>
    </div>
</div>

    <div class="mb-3 row">
        <label class='col-2 col-form-label'>Служба</label>
        <div class="col-10">
        <?php if(count($services) > 1) { ?>
            <select name="SERVICE" required class="custom-select form-select" id="SERVICE" >
                <?php foreach($services as $service) { ?>
                    <option value="<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></option>
                <?php } ?>
            </select>
        <?php } else { 
            $service = reset($services); ?>
            <input type="hidden" name="SERVICE" value="<?php echo $service->ID; ?>">
            <input type="text" name="" value="<?php echo $service->NAME; ?>" class='form-control' readonly>
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
    <div class='col-2'>Комментарий по результату работ</div>
    <div class="col-10">
        <input type="text" name="COMMENT" class="form-control">
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Ответственный исполнитель</div>
    <div class="col-10"><input type="text" name="OWNER" class="form-control" required></div>
</div>

<div class="row mb-5"  id="dateselector" <?php if(!$serviceRequest) {?> style="display:none" <?php } ?>>
    <div class='col-2'>Дата выполнения</div>
    <div class="col-10">
    <?php if($serviceRequest) {
        $PLANNED_DATE = date("d.m.Y", strtotime($serviceRequest->DATE_CREATE));
        if($serviceRequest->CRASH_ID) {
            $crash = Crash::find($serviceRequest->CRASH_ID);
            $PLANNED_DATE = date("d.m.Y", strtotime($crash->DATE));
        }
        ?>
        <input type="text" readonly name="PLANNED_DATE" value="<?php echo $PLANNED_DATE; ?>" class='form-control'> 
    <? } else {
			$this->view('components/select_date_history', [
                'fieldName' => 'PLANNED_DATE',
                'serviceId' => reset($services)->ID,
            ]);
    } ?>
    </div>
</div>


<input value="Добавить" type="submit" class='btn btn-primary'>
</form>



    <script>

    $(function() {
        $('#line').on('change', function() {
            if($('#line').val()) {
                lineId = $('#line').val();
				$("#dateselector").animate({height: 'show'}, 500);
                showStopDateLine();
            }
        });

        $('#SERVICE').on('change', function() {
        serviceId = $('#SERVICE').val();
        showStopDateLine();
    });
    });

    </script>


