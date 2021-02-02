<div class=''>
<form method="get" action="master_plan_date.php" onsubmit="return checkSubmit(this);">
<input type="hidden" name="step" value='2'>
<input type="hidden" name="save" value='1'>
<input type="hidden" name="mode" value='plan'>
<input type="hidden" name="service" value="<?php echo $this->service->ID; ?>">
<input type="hidden" name="date" value="<?php echo $this->date; ?>">

<div class="table-responsive mb-3 table-thead-fixed">
<table class="table table-bordered table-sm table-hover" id='table-master-plan'>
    <thead>
        <tr class='text-center'>
            <th><div>Наименование оборудования</div></th>
            <th><div>Название регламентной операции</div></th>
            <th><div>Тип операции</div></th>
            <th><div>Примечание</div></th>
            <th><div>Причина невыполнения</div></th>
            <th><div>Следующая дата выполнения</div></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operationsInLine as $lineName => $operations) { ?>
        <tr>
            <td class='table-warning text-center' colspan=100%>
                <?php echo $lineName; ?>
            </td>
        </tr>
        <?php foreach($operations as $operation) { 
            $day = $cookie['day'][$operation->ID] ??  date("d", strtotime($operation->PLANNED_DATE));
            $month = $cookie['month'][$operation->ID] ??  date("n", strtotime($operation->PLANNED_DATE));
            $year = $cookie['year'][$operation->ID] ??  date("Y", strtotime($operation->PLANNED_DATE));
            $operationDate = date("d.m.Y", mktime(0, 0, 0, $month, $day, $year));
            ?>
        <tr>
            <td><?php echo $operation->equipment ? $operation->equipment->path() : ''; ?></td>
            <td><?php echo $operation->NAME; ?></td>
            <td class='text-center'><?php echo Operation::getVerbalType($operation->TYPE_OPERATION); ?></td>
            <td><?php echo $operation->COMMENT; ?></td>
            <td><input type="text" name="COMMENT_NO_RESULT[<?php echo $operation->ID; ?>]" id="comment_no_result_<?php echo $operation->ID; ?>" class='comment form-control <?php echo $cookie['COMMENT_NO_RESULT'][$operation->ID] ? '' : 'is-invalid'; ?> w-100' onchange="checkComment(<?php echo $operation->ID; ?>)" value="<?php echo $cookie['COMMENT_NO_RESULT'][$operation->ID] ?? ''; ?>" required></td>
            <td class='text-center operation-date'>
                <input type="text" readonly class="form-control text-center <?php if($operationDate === date("d.m.Y", strtotime($operation->PLANNED_DATE))) echo "is-invalid"; ?>" value="<?php echo $operationDate; ?>" id="d<?php echo $operation->ID; ?>" style="background-color:#ffffff;">
				<a href="#" onClick="showCalendar(<?php echo $operation->LINE_ID . ", " . $operation->ID . ", " . $operation->SERVICE_ID; ?>); return false;">выбрать дату</a>
				<input type=hidden name="stoplinedate[<?php echo $operation->ID; ?>]" value="">
                <input type=hidden name="day[<?php echo $operation->ID; ?>]" value="<?php echo $day;?>">
                <input type=hidden name="month[<?php echo $operation->ID; ?>]" value="<?php echo $month;?>">
                <input type=hidden name="year[<?php echo $operation->ID; ?>]" value="<?php echo $year;?>">
            </td>
        </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>
</div>
    
    
    
    
</div>

<div class="row">
    <div class="col-6 pr-5 text-right">
        <button type="submit" class="btn btn-warning table-warning" style="background-color: #ffeeba; ">Перейти на шаг 3</button>
    </div>
    <div class="col-6 pl-5">
        <a href="?mode=plan&step=1&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>" class='btn btn-outline-secondary mr-5'>Вернуться на предыдущий шаг</a>
    </div>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
			</div>	
		</div>
		
	</div>
</div>

<script>
var operationId=0;

function showCalendar(line, operation, service) 
{
    operationId = operation;
	lineId = line;
	serviceId = service;
	showStopDateLine();
	$('#calendar').modal('show');
	$('#calendar').appendTo('body');
    $('#modal-wait').show();
    $('#modal-operations').hide();
	
}

function checkComment(id)
{
    if($('#comment_no_result_' + id).val() == '') {
        $('#comment_no_result_' + id).addClass('is-invalid');
    } else {
        $('#comment_no_result_' + id).removeClass('is-invalid');
    }
    
}

function checkSubmit(form)
{
    let error = '';
    if ($('#table-master-plan .is-invalid').length > 0) {
        error = 'Устраните ошибки';
    }
    if(error) {
        alert(error);
        return false;
    }
    return true;
}

var clickondate = function(el)
{
    $('#calendar').modal('hide');
    let day = el.attr('id').slice(6,8);
	let month = el.attr('id').slice(4,6);
	let year = el.attr('id').slice(0,4);
    $("#d" + operationId).val(day + "." + month + "." + year);
    if(day + "." + month + "." + year == '<?php echo date('d.m.Y', strtotime($this->date)); ?>') {
        $("#d" + operationId).addClass('is-invalid');
    } else {
        $("#d" + operationId).removeClass('is-invalid');
    }
	$("input[name='day[" + operationId + "]']").val(Number(day));
	$("input[name='month[" + operationId + "]']").val(Number(month));
	$("input[name='year[" + operationId + "]']").val(year);
	if ($("input[type=radio][name=createstopdate]:checked").val() == 1){
		$("input[name='stoplinedate[" + operationId + "]']").val(lineId);
	} else {
		$("input[name='stoplinedate[" + operationId + "]']").val(0);
	}	
	resetSelectedDate();
	$('input[type=radio][name=createstopdate][value=1]').prop('checked', true);
}



</script>