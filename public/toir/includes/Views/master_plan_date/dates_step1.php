<form method="post" action="master_plan_date.php" id='form-dates' onsubmit="return checkSubmit(this);">
<input type="hidden" name="step" value='1'>
<input type="hidden" name="save" value='1'>
<input type="hidden" name="mode" value='dates'>
<input type="hidden" name="date" value='<?php echo $this->date; ?>'>
<input type="hidden" name="service" value='<?php echo $this->service->ID; ?>'>

<div class='row my-5'>
    <div class="col-2 h5">Причина переноса даты</div>
    <div class="col-10"><input type="text" name="COMMENT" value="" class='form-control is-invalid'></div>
</div>

<div class="table-responsive mb-3 table-thead-fixed">
<table class="table table-bordered table-sm table-hover" id='table-dates'>
    <thead>
        <tr class='text-center'>
            <th><div>Наименование оборудования</div></th>
            <th><div>Название регламентной операции</div></th>
            <th><div>Тип операции</div></th>
            <th><div>Сменить дату</div></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operationsInLine as $lineName => $operations) { ?>
        <tr class='table-warning text-center'>
            <td colspan="3" class="align-middle">
                <?php echo $lineName; ?>
            </td>
            <td>
				<a href="#" onClick="showCalendar(<?php echo $lines[$lineName]; ?>); return false;" class='ml-3 btn btn-danger'>Выбрать дату</a>
                <input type=hidden name="new_date[<?php echo $lines[$lineName]; ?>]" value="" id='date<?php echo $lines[$lineName]; ?>'>
                <div id="d<?php echo $lines[$lineName]; ?>" class='my-2 font-weight-bold'></div>
            </td>
        </tr>
        <?php foreach($operations as $operation) { ?>
        <tr class="<?php echo ($operation->ID == $_REQUEST['selected'] || $operation->PLAN_ID == $_REQUEST['selected']) ? "table-info" :""?>" id='operation-<?php echo $operation->ID; ?>' operation="<?php echo $operation->ID; ?>">
            <td><?php echo $operation->equipment ? $operation->equipment->path() : ''; ?></td>
            <td><?php echo $operation->NAME; ?></td>
            <td class='text-center'><?php echo $operation->TYPE_OPERATION; ?></td>
            <td class='text-center'><input type="checkbox" name="operationId[<?php echo $lines[$lineName]; ?>][]" value="<?php echo $operation->ID; ?>"></td>
        </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>
</div>

<div class='mt-4 text-center'>
    <button type="submit" class="btn btn-info">Продолжить планирование</button>
</div>
</form>

<div class="modal fade" tabindex="-1" id='calendar'>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class='modal-body'>
		    <?php
			
	        $this->view('components/select_date', [
                'lineId' => 0,
                'monthsCount' => 3,
                'fieldName' => 'PLANNED_DATE',
                'createStopDate' => [1],
                'excludeDate' => $this->date,
		    ]);
	
		    ?>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Выбрать</button>
			</div>	
		</div>
		
	</div>
</div>

<script>


var clickondate = function(el){
    if((!$(el).hasClass("days"))&&(!$(el).hasClass("old"))) return;
	resetSelectedDate();
	$(el).addClass("selected");
	$("#h"+$( el).attr('id')).addClass("selected");
    var date=$( el).attr('id').slice(6,8)+"."+$( el).attr('id').slice(4,6)+"."+$( el).attr('id').slice(0,4);
    $('#d' + lineId).html(date);
    $('#date' + lineId).val(date);
}

function showCalendar(line) 
{
	lineId = line;
	showStopDateLine();
	$('#calendar').modal('show');
	$("#calendar").appendTo("body")
    $('#modal-wait').show();
    $('#modal-operations').hide();
}

function checkSubmit(form)
{
    let error = '';
    if (form.COMMENT.value == '') {
        error += 'Заполните комментарий';
    }
    if (error) {
        alert(error);
        return false;
    }
    return true;
}


</script>