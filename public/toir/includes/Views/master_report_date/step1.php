<h2 class='text-center pb-4'>Отчет "План работ на день профилактики" <?php echo d($this->date); ?></h2>
<h4 class='text-center pb-4'>Шаг 1</h4>

<div class=''>
<form method="get" action="master_report_date.php" id='form-step1' onsubmit="return checkStep1();">
<input type="hidden" name="step" value='2'>
<input type="hidden" name="save" value='1'>
<input type="hidden" name="next" value='step2'>
<input type="hidden" name="service" value="<?php echo $this->service->ID; ?>">
<input type="hidden" name="date" value="<?php echo $this->date; ?>">
<?php 
	if(DateProcessService::reportOutOfDate(array_values($this->dateProcesses)[0])){
	$outOfDate = true;
	?>
		Укажите причину просрочки планирования<br>
		<textarea class="form-control" id="commentExpired" name='REPORT_COMMENT_EXPIRED'><?php echo $cookie['REPORT_COMMENT_EXPIRED']; ?></textarea>
		<br>
<?}?>

<div class="table-responsive mb-3 table-thead-fixed">
<table class="table table-bordered table-sm table-hover" id='table-master-report'>
    <thead>
        <tr class='text-center'>
            <th><div>Наименование оборудования</div></th>
            <th><div>Название регламентной операции</div></th>
            <th><div>Выполнено</div></th>
            <th><div>Комментарий по результату работ</div></th>
            <th><div>Следующая дата выполнения</div></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operationsInLine as $lineName => $operations) { ?>
        <tr>
            <td class='table-success text-center' colspan=100%>
                <?php echo $lineName; ?>
            </td>
        </tr>
        <?php foreach($operations as $operation) { 
            $oId = $operation->ID;
            $day = $cookie['day'][$oId] ??  date("d", strtotime($operation->PLANNED_DATE));
            $month = $cookie['month'][$oId] ??  date("n", strtotime($operation->PLANNED_DATE));
            $year = $cookie['year'][$oId] ??  date("Y", strtotime($operation->PLANNED_DATE));
            $dateOperation = date("d.m.Y", mktime(0, 0, 0, $month, $day, $year));
            $done = !is_a($operation, Operation::class) || !is_array($cookie['done']) || in_array($oId, $cookie['done']);
            ?>
        <tr>
            <td><?php echo $operation->equipment ? $operation->equipment->path() : ''; ?></td>
            <td><?php echo $operation->NAME; ?></td>
            <td class='text-center'>
            <?php if(is_a($operation, Operation::class)) { ?>
                <div class="custom-control custom-switch" style='cursor:pointer;'>
                    <input type="checkbox" class="custom-control-input" id="customCheck_<?php echo $oId; ?>"  name="done[]" value='<?php echo $oId; ?>' <?php echo $done ? "checked" : ""; ?> onchange="changeDone(this); " style='cursor:pointer;' operation-id="<?php echo $oId; ?>">
                    <label class="custom-control-label" for="customCheck_<?php echo $oId; ?>" style='cursor:pointer;'><?php echo $done ? "Да" : "Нет"; ?></label>
                </div>
            <?php } else { ?>
                <input type="hidden" name="done[]" value='<?php echo $operation->ID; ?>'>
                Да
            <?php } ?>
            </td>
            <td class='text-center'>
                <input type="text" name="COMMENT[<?php echo $oId; ?>]" value="<?php echo $cookie['COMMENT'][$oId] ?? ''; ?>" id='comment-<?php echo $oId; ?>' class='form-control w-100' onchange="checkComment(<?php echo $oId; ?>)">
            </td>
            <td class='text-center select-date'>
                <input type="text" readonly class="form-control text-center <?php echo $done ? "d-none" : ""; ?> <?php if($dateOperation === date("d.m.Y", strtotime($operation->PLANNED_DATE))) echo "is-invalid"; ?>" value="<?php echo $dateOperation; ?>" id="d<?php echo $oId; ?>" style="background-color:#ffffff;">
                <a style="<?php echo $done ? "display:none" : ""; ?>" href="#" onClick="showCalendar(<?php echo $operation->LINE_ID . ", " . $oId . ", " . $operation->SERVICE_ID; ?>); return false;">Выбрать дату</a>
                <input type=hidden name="day[<?php echo $oId; ?>]" value="<?php echo $day;?>">
                <input type=hidden name="month[<?php echo $oId; ?>]" value="<?php echo $month;?>">
                <input type=hidden name="year[<?php echo $oId; ?>]" value="<?php echo $year;?>">
				<input type=hidden name="stoplinedate[<?php echo $oId; ?>]" value="0">
	        </td>
        </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
    <tfoot>
		 <tr>
            <td colspan=100%>
            <div class='text-center table-success rounded border border-success' style="width:380px;">
                    <button class="btn" onClick="addOperation(); return false">Добавить / редактировать / удалить операции</button>
                </div>
            </td>
        </tr>
    </tfoot>
 </table>
</div>
    

<div class='mt-4 text-center'><button type="button" class="btn btn-success" onclick="goStep2();">Перейти на шаг 2</button></div>
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
var operationId=0
var clickondate="";
function showCalendar(line,operation, service) 
{
    //$('#operations-detail h5 span').html(String(service_request_id));
    operationId=operation;
	lineId=line;
	serviceId = service;
	showStopDateLine();
	$('input[type=radio][name=createstopdate][value=1]').prop('checked', true);
	$('#calendar').modal('show');
    $('#calendar').appendTo('body');
    $('#modal-wait').show();
    $('#modal-operations').hide();
}

clickondate= function(el){
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

function addZero(num)
{
    return Number(num) < 10 ? '0' + num : num;
}

function checkStep1()
{
    var error = '';
    $('#form-step1 input[type="checkbox"]').each(function(index, item) {
        if(!$(item).prop('checked')) {
            let operationId = $(item).attr('operation-id');
            let comment = $('#comment-' + operationId);
            if (!comment || comment.val() == '') {
                error = 'Если операция не выполнена, то комментарий обязателен';
            }
            if ($('#d' + operationId).hasClass('is-invalid')) {
                error = 'Выберите новую дату';
            }
	     }
    });
	
	<?php if($outOfDate){ ?>
	if(!$("#commentExpired").val()){
		error = "Укажите причину просрочки планирования!";
		$("#commentExpired").css("border","1px solid red");	
		$("#commentExpired").focus();
	}
	<?php } ?>

    if(error) {
        alert(error);
        return false;
    } else {
        return true;
    }
}

function goStep2()
{
    if(checkStep1()) {
        $('#form-step1').submit();
    }
}

function changeDone(checkbox)
{
    if (checkbox.checked) {
        $(checkbox).parent().find('label').html('Да');
    } else { 
        $(checkbox).parent().find('label').html('Нет');
    }
    let div = $(checkbox).parent().parent().parent();
    if($(checkbox).prop('checked')) {
        div.find('.select-date a').hide();
        $('#d' + checkbox.value).addClass('d-none');
    } else {
        div.find('.select-date a').show();
        $('#d' + checkbox.value).removeClass('d-none');
    }

    checkComment($(checkbox).val());
}

function checkComment(id)
{
    if(!$('#customCheck_' + id).prop('checked') && $('#comment-' + id).val() == '') {
        $('#comment-' + id).addClass('is-invalid');
    } else {
        $('#comment-' + id).removeClass('is-invalid');
    }
    
}

function addOperation()
{
    $("#form-step1").attr('target', '_blank');
    $("#form-step1").find('input[name="next"]').val('add_operation_group');
    $("#form-step1").submit(); 
}

</script>