<?php $this->view('components/select_equipment', ["multiply"=>true]); ?>
<h1 class='text-center mb-5'>Добавление операций</h1>

<div class="mb-3">
    Служба: <?php echo $this->service->NAME; ?>
</div>

<form action="" method="POST" id="operations-form">
<input type="hidden" name="action" value="save">
<div class="table-responsive">
<table class="table table-bordered table-hover" id="operations-table">
    <thead>
        <tr class='text-center'>
            <th></th>
            <th width="17%">Наименование оборудования</th>
            <th>Операции без даты</th>
            <th>Название операции</th>
            <th>Рекомендации</th>
            <th>Тип операции</th>
            <th>Дата</th>
            <th></th>
        </tr>
    </thead>
    <tbody></tbody>
    <tfoot>
        <tr>
            <td colspan="100%"><a href="#" onclick="operationGroupAdd({}); return false;" class="btn btn-outline-primary">Добавить новую операцию</a></td>
        </tr>
    </tfoot>
</table>
</div>
</form>

<div class="mt-4 text-center">
    <a href="#" onclick="validateForm();" class="btn btn-primary">Сохранить</a>
</div>

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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
			</div>	
		</div>
		
	</div>
</div>

<div class="modal fade" tabindex="-1" id='new_work'>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class='modal-body'>
                <?php $this->view('add_work/_add'); ?>
                <input type="hidden" id="row_id" value="">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="storeWork();">Добавить</button>
			</div>	
		</div>
		
	</div>
</div>

<script>
var dateRow = null;

function operationGroupAdd(values)
{
    $.ajax({
        type: "POST",
        url: "add_operation_group.php",
        data:{
            action: 'newRow',
            service: <?php echo $this->service->ID; ?>,
            date: '<?php echo $this->date; ?>',
            values: values
        },
        dataType: 'html',
        success: function ( data ) {
            $('#operations-table tbody').append(data);
            let addedRow = $('#operations-table tbody').find('tr').last();
            let inputEquipment = addedRow.find('input[name="equipment[' + addedRow.data('id') + ']"]');
            makeEquipmentHref(inputEquipment);
            inputEquipment.on('change', function (event) {
                changeEquipment(event.target, 0);
            });
            changeEquipment(inputEquipment, values.WORK_ID);
        }
    });    
}

function operationGroupRemove(link)
{
    $(link).parent().parent().remove();
}

function operationGroupCopy(link)
{
    let row = $(link).parent().parent();
    let values = {
        EQUIPMENT_ID: row.find('input[name="equipment[' + row.data('id') + ']"]').val(),
        NAME: row.find('textarea[name="NAME[' + row.data('id') + ']"]').val(),
        RECOMMENDATION: row.find('textarea[name="RECOMMENDATION[' + row.data('id') + ']"]').val(),
        TYPE_OPERATION_ENUM: row.find('select[name="TYPE_OPERATION[' + row.data('id') + ']"]').val(),
        PLANNED_DATE: row.find('input[name="PLANNED_DATE[' + row.data('id') + ']"]').val(),
    };
    operationGroupAdd(values);
}

function changeEquipment(input, workId) 
{
    getWorks($(input).val(), $(input).parent().parent().data('id'), workId)
}

function getWorks(equipment, id, workId) 
{
    $.ajax({
        type: "POST",
        url: "add_operation_group.php",
        data:{
            action: 'getWorks',
            service: <?php echo $this->service->ID; ?>,
            equipment: equipment,
            id: id,
            workId: workId
        },
        dataType :'html',
        success: function ( data ) {
            $('#operations-table').find('tr[data-id="' + id + '"]').find('.works').html(data);
        }
    });
}

function changeWork(input)
{
    let row = $(input).parent().parent().parent();
    let id = row.data('id');

    if($(input).val() == '') {
        row.find('textarea[name="NAME[' + id + ']"]').val('');
        row.find('textarea[name="RECOMMENDATION[' + id + ']"]').val('');
        row.find('select[name="TYPE_OPERATION[' + id + ']"]').val('');
    } else {
        row.find('textarea[name="NAME[' + id + ']"]').val($(input).parent().find('span').html());
        row.find('textarea[name="RECOMMENDATION[' + id + ']"]').val($(input).data('recommendation'));
        row.find('select[name="TYPE_OPERATION[' + id + ']"]').val($(input).data('type'));
    }
}

function newWork(equipmentId, equipmentName, id)
{
	$('#new_work').modal('show');
	$('#new_work').appendTo('body');
    $('#equipment-name').html(equipmentName);
    $('#equipment-id').val(equipmentId);
    $('#new_work').find('#row_id').val(id);
    $('#new_work').find('select[name="SERVICE_ID"]').val('<?php echo $this->service->ID; ?>');
    $('#new_work').find('select[name="SERVICE_ID"]').prop('disabled', true);
}

function storeWork()
{
    $.ajax({
        type: "POST",
        url: "add_work.php",
        data:{
            action: 'storeAjax',
            equipment: $('#equipment-id').val(),
            SERVICE_ID: '<?php echo $this->service->ID; ?>',
            NAME: $('#new_work').find('input[name="NAME"]').val(),
            TYPE: $('#new_work').find('select[name="TYPE"]').val(),
            RECOMMENDATION: $('#new_work').find('input[name="RECOMMENDATION"]').val(),
        },
        dataType :'json',
        success: function ( response ) {
            if(response.errors) {
                alert("Операция НЕ добавлена\n\n" + response.errors);
            } else {
                getWorks($('#equipment-id').val(), $('#new_work').find('#row_id').val(), 0) 
                $('#equipment-name').html('');
                $('#equipment-id').val('');
                $('#new_work').find('input[name="NAME"]').val('');
                $('#new_work').find('select[name="TYPE"]').val('');
                $('#new_work').find('input[name="RECOMMENDATION"]').val('');
            }
        }
    });
}

function showCalendar(input) 
{
    dateRow = $(input).parent().parent();

	var eqipment = dateRow.find('input[name="equipment[' + dateRow.data('id') +']"]').val();
	if(!eqipment){
		alert("Выберите оборудование!");
		return false;
    }
	lineId = cashedLines[eqipment];
	if(!lineId){
		alert("Не задана линия!");
		return false;
	}
	serviceId = <?php echo $this->service->ID; ?>;
	showStopDateLine();
	$('#calendar').modal('show');
	$('#calendar').appendTo('body');
    $('#modal-wait').show();
    $('#modal-operations').hide();
	
}

function validateForm() 
{
    var error = "";
    $('#operations-table tbody tr').each(function( index, element ) {
        let id = $(element).data('id');
        
        let name = $(element).find('input[name="NAME[' + id + ']"]');
        if (name.length && !name.val()) {
            name.addClass("is-invalid");	
            error = error + "\nНе указано название опрерации!";	
        } else if(name.length && name.val()) {
            name.removeClass("is-invalid");	
        }
        
        let equipment = $(element).find('input[name="equipment[' + id + ']"]');
        if (equipment.length && !equipment.val()) {
            $(element).find(".equipment-select-modal").css("color","red");	
            error = error + "\nНе выбрано оборудование!";	
        } else if(equipment.length && equipment.val()) {
            $(element).find(".equipment-select-modal").css("color","#2067b0");		
        }

        let date = $(element).find('input[name="PLANNED_DATE[' + id + ']"]');
        if (date.length && !date.val()) {
            date.addClass("is-invalid");	
            error = error + "\nНе выбрана дата!";	
        } else if(date.length && date.val()) {
            date.removeClass("is-invalid");	
        }    
    });
    
    if(error) {
        alert(error);
    } else {	
        $('#operations-form').submit();
    }		
}


var clickondate = function(el)
{
    $('#calendar').modal('hide');
    let day = el.attr('id').slice(6,8);
	let month = el.attr('id').slice(4,6);
	let year = el.attr('id').slice(0,4);
	let input = dateRow.find('input[name="PLANNED_DATE[' + dateRow.data('id') + ']"]');
	input.val(day + "." + month + "." + year);
    resetSelectedDate();
    dateRow = null;
}

$(document).ready(function() {
    <?php if(!empty($operations)) { ?>
        <?php foreach($operations as $operation) { ?>
            operationGroupAdd(<?php echo json_encode($operation); ?>);
        <?php } ?>
    <?php } else { ?>
        operationGroupAdd({
            'EQUIPMENT_ID': "<?php echo $this->sourceModel ? $this->sourceModel->EQUIPMENT_ID : '' ?>",
        });
    <?php } ?>
});

</script>