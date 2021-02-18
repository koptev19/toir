<?php
$this->view('_header', ['title' => "Добавление операций в Журнал работ"]);
$this->view('components/select_equipment', ["multiply"=>true]);
?>

<h1 class='text-center mb-5'>Добавление операций в Журнал работ</h1>

<div class="mb-3">
    Служба: <?php echo $this->sourceModel->service->NAME; ?>
</div>

<form action="" method="POST" id="operations-form">
<input type="hidden" name="action" value="save">
<div class="table-responsive">
<table class="table table-bordered table-hover" id="operations-table">
    <thead>
        <tr class='text-center'>
            <th></th>
            <th>Наименование оборудования</th>
            <th>Работы</th>
            <th>Название операции</th>
            <th>Тип операции</th>
            <th>Комментарий по результату</th>
            <th>Ответственный исполнитель</th>
            <th>Время от и до</th>
            <th>Дата</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="100%"><a href="#" onclick="historyGroupAdd(); return false;" class="btn btn-outline-primary">Добавить новую операцию</a></td>
        </tr>
    </tfoot>
</table>
</div>
</form>

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


<div class="mt-4 text-center">
    <a href="#" onclick="validateForm();" class="btn btn-primary">Сохранить</a>
</div>

<script>

function historyGroupAdd(values)
{
    $.ajax({
        type: "POST",
        url: "add_history_group.php",
        data: {
            action: 'newRow',
            <?php echo get_class($this->sourceModel) == "Downtime"? "downtime_id" : "service_request"   ?>: <?php echo $this->sourceModel->ID; ?>,
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
                changeEquipment(event.target);
            });
            changeEquipment(inputEquipment);
        }
    });    
}

function historyGroupRemove(link)
{
    $(link).parent().parent().remove();
}

function historyGroupCopy(link)
{
    let row = $(link).parent().parent();
    let values = {
        EQUIPMENT_ID: row.find('input[name="equipment[' + row.data('id') + ']"]').val(),
        NAME: row.find('textarea[name="NAME[' + row.data('id') + ']"]').val(),
        COMMENT: row.find('input[name="COMMENT[' + row.data('id') + ']"]').val(),
        OWNER: row.find('input[name="OWNER[' + row.data('id') + ']"]').val(),
        TYPE_OPERATION: row.find('select[name="TYPE_OPERATION[' + row.data('id') + ']"]').val(),
        time_from: row.find('input[name="time_from[' + row.data('id') + ']"]').val(),
        time_to: row.find('input[name="time_to[' + row.data('id') + ']"]').val(),
    };
    historyGroupAdd(values);
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
            SERVICE_ID: '<?php echo $this->sourceModel->SERVICE_ID; ?>',
            NAME: $('#new_work').find('input[name="NAME"]').val(),
            TYPE: $('#new_work').find('select[name="TYPE"]').val(),
            RECOMMENDATION: $('#new_work').find('input[name="RECOMMENDATION"]').val(),
        },
        dataType :'json',
        success: function ( response ) {
            if(response.errors) {
                alert("Операция НЕ добавлена\n\n" + response.errors);
            } else {
                getWorks($('#equipment-id').val(), $('#new_work').find('#row_id').val()) 
                $('#equipment-name').html('');
                $('#equipment-id').val('');
                $('#new_work').find('input[name="NAME"]').val('');
                $('#new_work').find('select[name="TYPE"]').val('');
                $('#new_work').find('input[name="RECOMMENDATION"]').val('');
            }
        }
    });
}

function changeEquipment(input) 
{
    getWorks($(input).val(), $(input).parent().parent().data('id'))
}

function getWorks(equipment, id) 
{
    $.ajax({
        type: "POST",
        url: "add_operation_group.php",
        data:{
            action: 'getWorks',
            service: <?php echo $this->sourceModel->SERVICE_ID; ?>,
            equipment: equipment,
            id: id,
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
        row.find('select[name="TYPE_OPERATION[' + id + ']"]').val('');
    } else {
        row.find('textarea[name="NAME[' + id + ']"]').val($(input).parent().find('span').html());
        row.find('select[name="TYPE_OPERATION[' + id + ']"]').val($(input).data('type'));
    }
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

        let type = $(element).find('select[name="TYPE_OPERATION[' + id + ']"]');
        if (!type.val()) {
            type.addClass("is-invalid");	
            error = error + "\nНе выбран тип операции!";	
        } else {
            type.removeClass("is-invalid");	
        }    

        let time_from = $(element).find('input[name="time_from[' + id + ']"]');
        let time_to = $(element).find('input[name="time_to[' + id + ']"]');
        time_from.removeClass("is-invalid");	
        time_to.removeClass("is-invalid");	
        if (!time_from.val() || !time_to.val()) {
            time_from.addClass("is-invalid");	
            time_to.addClass("is-invalid");	
            error = error + "\nНе выбрано время!";	
        } else if(time_to.val() < time_from.val()) {
            time_from.addClass("is-invalid");	
            time_to.addClass("is-invalid");	
            error = error + "\nВремя \"от\" должно быть меньше времени \"после\"!";	
        }
    });
    
    if(error) {
        alert(error);
    } else {	
        $('#operations-form').submit();
    }		
}

$(document).ready(function() {
    historyGroupAdd({
        'EQUIPMENT_ID': "<?php echo $this->sourceModel->EQUIPMENT_ID; ?>",
    });
});


</script>

<?php $this->showFooter(); ?>
