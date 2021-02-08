<?php $this->view('components/select_equipment', ["multiply"=>true]); ?>
<h1 class='text-center mb-5'>Добавление операций</h1>

<div class="mb-3">
    Служба: <?php echo $this->service->NAME; ?>
</div>

<form action="" method="POST" id="operations-form">
<div class="table-responsive">
<table class="table table-bordered table-hover" id="operations-table">
    <thead>
        <tr class='text-center'>
            <th></th>
            <th width="17%">Наименование оборудования</th>
            <th>Операции без даты</th>
            <th>Название операции</th>
            <th>Тип операции</th>
            <th>Дата</th>
            <th>ТМЦ</th>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
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
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="storeWork();">Добавить</button>
			</div>	
		</div>
		
	</div>
</div>


<div class="modal fade" tabindex="-1" id='details'>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
           <div class="modal-header d-block">
				<div id="find" class="row mb-4 mt-3">
		            <div class="col-6">
				        <input id="findvalue" type="text" placeholder="Название детали" class="form-control">
						
				</div>
				<div class="col-6">
			    <select id="storeSelect" name="store" onchange="changeStore()" class="form-control">
                    <option value="Remnantsbtx24Sklad_tekhnicheskikh_materialov.xml">Склад технических материалов</option>
                    <option value="Remnantsbtx24TsLSh_Romanov.xml">ЦЛШ Романов</option>
					<option value="Remnantsbtx24TsKF_Knyazkov.xml">ЦКФ Князьков</option>
					<option value="Remnantsbtx24Birzha_syrya_Borisov.xml">Биржа сырья Борисов</option>
				</select>
				</div>
				</div>
		   </div>
		   <div class='modal-body'>
					<div style="position: absolute;top: 50%; left:50%; display:none" class="spinner-border" id='waitLoading' role="status">
					  <span class="sr-only">Loading...</span>
					</div>
					<table class='table table-bordered table-sm table-hover' id='detailTable'>
					</table>
			</div>
			<div class="modal-footer">
                <button type="button" id="close" class="btn btn-secondary" onCLick="closeWindow();">Закрыть</button>
				<button type="button" onClick="hideLines();" class="btn btn-primary">Сохранить</button>
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
        url: "instant_writeoff.php",
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

		/*console.log(id);
		console.log(resultArray);
		if(resultArray[id] === undefined){
			error = "\nНеобходимо указать списание у всех операций!"
		}*/		
    });
    
    if(error) {
        alert(error);
    } else {	
        submitForm();
    }		
}

function submitForm(){

	$.ajax({
        url:      "instant_writeoff.php?"+$("#operations-form").serialize(),
        type:     "POST", 
        dataType: "json", 
        data: {
			//form:$("#operations-form").serialize(),
			action: 'save',
			service : <?php echo $this->service->ID; ?>,
			delay_writeoff: delayedWriteoff
		},		
        success: function(response) { 
        	var deleteOp ={};
			var saveTofile = {};
			for(i in response){
				if(resultArray[i]!==undefined){
					saveTofile[response[i]] = resultArray[i];
				}
			}
			saveFile(saveTofile,response);
    	},
    	error: function(response) { // Данные не отправлены
            
    	}
 	});


}


function deleteOperations(operations){

	$.ajax({
        url:      "instant_writeoff.php",
        type:     "POST", 
        dataType: "json", 
        data: {
			action: 'deleteOperations',
			service: <?php echo $this->service->ID; ?>,
			operations: operations 
		},		
        success: function(response) { 
    	
		},
    	error: function(response) { // Данные не отправлены
            
    	}
 	});


}



var clickondate = function(el)
{
    $('#calendar').modal('hide');
    let day = el.attr('id').slice(0,2);
	let month = el.attr('id').slice(2,4);
	let year = el.attr('id').slice(4,8);
	let input = dateRow.find('input[name="PLANNED_DATE[' + dateRow.data('id') + ']"]');
	input.val(day + "." + month + "." + year);
    resetSelectedDate();
    dateRow = null;
}

//tmc

	var allOperations={};
	<?php foreach($operationsInLine as $lineName => $operations) {
		 foreach($operations as $operation) { ?>
			allOperations['<?php echo $operation->ID ?>']=1;	
		<?php }
	} ?>
	var saveStep=1;
	var operation=0;
	var result="";
	var resultArray={};
	var storeArray={};
	var saveArray=[];
	var begin=0;
	var end=500;
	var scroll_top = $(".modal-body").scrollTop();//высота прокрученной области
	var wind_height = $(".modal-body").height();
	var scrol =0;
	var curentStore="Remnantsbtx24Sklad_tekhnicheskikh_materialov.xml";
	var delayedWriteoff = {};
	
	
	
	
	function showDetailList(op){
		saveStep=1;
		operation=op;
		$("#close").html("Закрыть");
		$('#details').modal('show');
		$('#details').appendTo('body');
		storeArray= (resultArray[operation] !== undefined) ? CopyObj(resultArray[operation]) : {};
		getDetails("Remnantsbtx24Sklad_tekhnicheskikh_materialov.xml");
		$('#modal-wait').show();
		$('#modal-operations').hide();
		$("#detailTable tr").show();
		$("#find").show();
		$("#findvalue").val("");
		$('#storeSelect option[value="Remnantsbtx24Sklad_tekhnicheskikh_materialov.xml"]').prop('selected', true);
		$("#detailTable").find("input[name='quantity']").val("");

	}
	
	function noWriteOff(op){
		resultArray[op]={};
		$("#op"+op).html("Списание не требуется");
		delete delayedWriteoff[op];
	}
	
	function delayWriteoff(op){
		resultArray[op]={};
		delayedWriteoff[op] = 1;
		$("#op"+op).html("Списание позже");
	}
	
	function closeWindow(){
		if(saveStep==1){	
			$('#details').modal('hide');
		}else{
			$("#detailTable tr").show();
			saveStep=1;
			getDetails($("#storeSelect").val());
			$("#close").html("Закрыть");
			$("#detailTable").find("input[name='quantity']").prop('disabled', false);
			$("#find").show();
		}

	}
	
	function saveToArray(){
		var find=false;
		var quantity=[];
		var saveArray={};
		
		$("#detailTable tr#detailrow").each(function(index,el) {
			if($(el).find("input[name='quantity']").val()>0){
				console.log($(el).find("input[name='guid']").val());
				saveArray[$(el).find("input[name='guid']").val()]=$(el).find("input[name='quantity']").val();
				find=true;
		   	}
		});	
		
		if (find){
			storeArray[curentStore]=saveArray;
		}else{
			storeArray[curentStore] ={};
		}
	}

	function hideLines(){
	
		if(saveStep==1){
			var k=0;
			$("#detailTable tr#detailrow").each(function(index,el) {
				if(!$(el).find("input[name='quantity']").val()){
					$(el).hide();
				}else{
					$(el).show();
					result+=$(el).find("#name").html()+"("+$(el).find("input[name='quantity']").val()+")<br>";
					saveArray[k++]=$(el).find("input[name='guid']").val()+"/"+$(el).find("input[name='quantity']").val()
		    		$(el).find("input[name='quantity']").prop('disabled', true);
					
				}
			});	
			saveToArray();
			if(!checkStore()){
				alert("Надо что-то списать!");
				return false;
			}
			showResults();
			$("#find").hide();
			$("#findvalue").val("");
			$("#close").html("Редактировать");
			saveStep=2;
		}else{
			$('#details').modal('hide');
			$("#op"+operation).html($("#detailTable").html());
			resultArray[operation]=CopyObj(storeArray);
			resultArray[operation]=storeArray;
			//saveFile();
			saveStep=1;
			delete delayedWriteoff[operation];
		}
	}

	function checkStore(){
		for (var prop in storeArray) {
		  	if (!jQuery.isEmptyObject(storeArray[prop]))
			{
				return true;				
			}
		} 
	return false; 	
	}


	function checkResult(){
		for (var prop in allOperations) {
			if (resultArray[prop] === undefined)
			{
				return false;
			}
		}
		return true;	
	}
	
	function showResults(){
        $.ajax({
            type: "POST",
            url: "tmc/tmc2.php",
            data: {
              	RESULT:1,
				DATA:storeArray,
				
			},
            dataType :'html',
            success: function (data) {
				var i=0;
				//console.log(data);
				$("#detailTable").html(data);
			}
         });
    }	
	
	function saveFile(arr,deleteOp){
       	$.ajax({
            type: "POST",
            url: "tmc/tmc2.php",
            data: {
              	SAVEFILE:1,
				DATA:arr,
				DELETEOPERATIONS : true
            },
            dataType :'html',
            success: function (data) {
				deleteOperations(deleteOp);
				window.location.href = "?action=close&service=<?php echo $this->service->ID ?>";
			}
         });
    }	

	function getDetails(store){
        curentStore=store;
		//console.log(JSON.stringify(resultArray));
		$.ajax({
            type: "POST",
            url: "tmc/tmc2.php",
            data: {
              	AJAX:1,
				STORE:store,	
				RESULT:JSON.stringify(storeArray),
            },
            dataType :'html',
            success: function (data) {
				var i=0;
				$("#detailTable").html(data);
			}
         });
    }	

	function loadFilesFtp(){
		$.ajax({
            type: "POST",
            url: "tmc/tmc2.php",
            data: {
              	GETFILES:1,
            },
            dataType :'html',
        });
    }	

	function showInput(el){
		$(el).children("div").hide();
		$(el).children("input").show();
		$(el).children("input").focus();

	}
	
	function changeStore(){
		saveToArray();
		$("#detailTable").html("");
		getDetails($("#storeSelect").val());
		$("#find").val("");
	}
	
	function findString(){
		var find=String($("#findvalue").val());
		console.log(find);
		//$("#waitLoading").hide();
		if(find.length==0){
			$("#detailTable tr#detailrow").show();
			return;
		}		
		$("#detailTable tr#detailrow").each(function(index,el) {
			
			var regV = new RegExp('.*'+find+'.*','gi'); 
			var search=$(el).children()[1];
			search=$(search).html();
			var result = search.match(regV);
				if(result){
					$(el).show();
				}else{
					$(el).hide();
				}
		});
		
		console.log("sss");
		//$("#waitLoading").hide();	
	}


	function CopyObj(src) {
		 return Object.assign({}, src);
	}


$(document).ready(function() {
        operationGroupAdd({
            'EQUIPMENT_ID': "<?php echo $this->sourceModel ? $this->sourceModel->EQUIPMENT_ID : '' ?>",
        });

		$("#find").keyup(function(event){
			    
				if (event.key === "Enter") {
					findString();
					$("#waitLoading").show();
					setTimeout(() => $("#waitLoading").hide(), 500);
				}else if(!$("#findvalue").val()){
					findString();
					$("#waitLoading").show();
					setTimeout(() => $("#waitLoading").hide(), 500);
				}

		});
		
		loadFilesFtp();
		

});





</script>