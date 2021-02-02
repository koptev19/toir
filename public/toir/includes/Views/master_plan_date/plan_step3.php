<?php

$countWorkers = 0;
if(!empty($cookie['workers'])) {
	foreach($cookie['workers'] as $k => $v) {
		$countWorkers = $k;
	} 
}

if(!$countWorkers||$countWorkers<4)  {
    $countWorkers = 4;
}

$times = json_decode($cookie['result'], true);
?>


<style>
.workerListItem a {
	display:none;
	cursor: pointer;
	float:right;
}

.workerListItem:hover a {
	display:inline;
}
</style>


<div class=''>
<form method="post" action="master_plan_date.php" id='form'>
<input type="hidden" id="result" name="result" value=''>
<input type="hidden" name="step" value='3'>
<input type="hidden" id='stepBack' name="stepBack" value='0'>
<input type="hidden" name="save" value='1'>
<input type="hidden" name="mode" value='plan'>
<input type="hidden" name="service" value="<?php echo $this->service->ID; ?>">
<input type="hidden" name="date" value="<?php echo $this->date; ?>">

<div class='text-right'>
	Исполнители 
	<a href="#" onclick="addWorker(); return false;" class='font-weight-normal ml-3'><img src='images/plus.svg' style="width:24px;"></a>
	<a href="#" onclick="removeWorker(); return false;" class='font-weight-normal ml-3'><img src='images/x.svg'></a>
</div>
            
<div class="table-responsive mb-3 table-thead-fixed">
<table class="table table-bordered table-sm table-hover" id='table-operations'>
    <thead>
        <tr class='text-center' id='rowHead'>
            <th><div>Наименование оборудования</div></th>
            <th><div>Название регламентной операции</div></th>
            <th><div>Время выполнения</div></th>
          </tr>
    </thead>
    <tbody>
    <?php foreach($operationsInLine as $lineName => $operations) { ?>
        <tr class='line-name'>
            <td class='table-warning text-center' colspan=100%>
                <?php echo $lineName; ?>
            </td>
        </tr>
        <?php foreach($operations as $operation) { ?>
        <tr class='operation' operation-id='<?php echo $operation->ID; ?>'>
            <td style="height:65px"><?php echo $operation->equipment ? $operation->equipment->path() : ''; ?></td>
            <td><?php echo $operation->NAME; ?></td>
            <td class='text-nowrap text-center time' id='time'>
            </td>
        </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>
</div>

<div class="row">
    <div class="col-6 pr-5 text-right">
        <a href="#" onClick="submitForm(false)" class="btn btn-warning table-warning" style="background-color: #ffeeba; ">Перейти на шаг 4</a>
    </div>
    <div class="col-6 pl-5">
        <a href="#" onClick="$('#stepBack').val('1'); submitForm(true)" class='btn btn-outline-secondary mr-5'>Вернуться на предыдущий шаг</a>
    </div>
</div>

    
</form>

<div class='d-none' id='cell-template'>
    <div class="custom-control custom-switch" style='cursor:pointer;'>
        <input type="checkbox" class="custom-control-input" id="{checkboxid}"  name="" value='' onchange="changeCheckboxDone(this)" style='cursor:pointer;'>
        <label class="custom-control-label" for="{checkboxid}" style='cursor:pointer;'>Нет</label>
    </div>
    <div class='selecttime' style='display:none'>
	<input type='time' name='begin' class='mr-2' value='08:00' >
    <input type='time' name='end' class='ml-2' value='09:00'>
	</div>
</div>

<?php 
$workers = $this->service->workers;
?>
<div class="modal fade" tabindex="-1" id='workers'>
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

			<div class='modal-body'>
				<div class="row mb-4">
					<div class="col-6">
						<input type="text" id="newWorker" class="form-control" placeholder="Новый исполнитель">
					</div>
					<div class="col-6">
						<button type="button" onClick="addWorkerToList();" class="btn btn-primary">Добавить</button>
					</div>
				</div>
				<div id="workerList" class="mb-3">
					<?php 
					foreach($workers as $worker){ 
						?>
					<div id="workerListItem<?php echo $worker->id ?>" class="workerListItem">
						<input data-name="<?php echo $worker->name; ?>" value=<?php echo $worker->id ?> type='checkbox'>&nbsp;&nbsp;&nbsp;<?php echo $worker->name; ?>
						<a href="#" onClick='deleteWorker(<?php echo $worker->id ?>)'>
						<img src='images/x.svg'>
						</a>
					</div>
					<?php }?>
				</div>
                <button type="button" onClick="selectWorkersDone();" class="btn btn-primary">Выбрать</button>
			</div>	
		</div>
		
	</div>
</div>


<script>

var numWorker = 0;
var classDiv = '';
var currentWorker = 0;
var selectedWorkers =[];
var workersName =[];

function updateNames(numWorker){
	var names= "";
	if (selectedWorkers[numWorker] === undefined ) return false;
	$("#workersNames"+numWorker).html(""); 
		for(let j=0 ; j<selectedWorkers[numWorker].length; j++){
			$("#workersNames"+numWorker).append(
			"<a href='#' onClick='selectWorker("+ numWorker +")'>"+workersName[selectedWorkers[numWorker][j]]+"</a><br>"+
			"<input type=hidden value='"+selectedWorkers[numWorker][j]+"' name=workers["+numWorker+"][]>");
			names += (names.length ? ", ":"")+workersName[selectedWorkers[numWorker][j]];
		}
		if(!$("#workersNames"+numWorker).html()){
		$("#workersNames"+numWorker).html("<a href='#' onClick='selectWorker("+ numWorker +")'>Исполнитель " + currentWorker + "</a>");
		}	
	
		$("#workersNames"+numWorker).append(
		"<input type=hidden value='"+ names +"' name=workersNames["+numWorker +"]>");
	
}

function deleteWorkerFromArray(id){
	var arr =[];
	var name = names = "ssss";
	$('#workerListItem'+id).remove();
	
	for(let i=1 ; i<selectedWorkers.length; i++){
		if (selectedWorkers[i] === undefined ) continue;
		arr = [];
		for(let j=0 ; j<selectedWorkers[i].length; j++){
			if(selectedWorkers[i][j] == id) continue;  
			arr.push(selectedWorkers[i][j]); 
		}
		selectedWorkers[i] = arr;
		//updateNames(i);
	}
	restoreWorkersNames();
}

function deleteWorker(id){
	if(!confirm('Удалить?')) return false;
	$.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            action: 'deleteWorker',
            id: id,
        },
        success: function () {
			deleteWorkerFromArray(id);
	   }
    });
	
}

function addWorkerToList(){
	if(!$("#newWorker").val()) return false;
	$.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            action: 'addWorker',
            workerName: $("#newWorker").val(),
			serviceId:<?php echo $this->service->ID; ?>
        },
        dataType :'json',
        success: function (data) {
			$("#workerList").prepend(			
				"<div id='workerListItem"+data.id+"' class='workerListItem'>"+
				"<input checked data-name='"+data.name+"' value='"+data.id+"' type='checkbox'>"+
				"&nbsp;&nbsp;&nbsp;"+data.name+
				"<a href='#' onClick='deleteWorker("+data.id+")'><img src='images/x.svg'></a>"+	
				"</div>");        
				$("#newWorker").val("");
        }
    });
	
}


function hideBusyWorkers(worker){
	$(".workerListItem").show();
	for(let i=1 ; i<selectedWorkers.length; i++){
		if (i == worker || selectedWorkers[i] === undefined ) continue;
		for(let j=0 ; j<selectedWorkers[i].length; j++){
			$("#workerListItem"+selectedWorkers[i][j]).hide();
		}
	}	
}

function selectWorker(worker){
	currentWorker = worker;
	$('#workers input:checkbox').prop('checked', false);
	$('#workers').modal('show');
	$("#workers").appendTo("body")
	//hideBusyWorkers(worker);
	if(selectedWorkers[worker] === undefined) return false;
	for(let i=0 ; i<selectedWorkers[worker].length; i++){
		$("#workerListItem"+selectedWorkers[worker][i]).show();
		$('#workers').find("input:checkbox[value='"+selectedWorkers[worker][i]+"']").prop('checked', true);	
	}	
}


function selectWorkersDone(){
	var res=[];
	var names = "";
	$('#workers input:checkbox').each(function(index, item) {
		if ($(item).prop("checked"))
		{
			res.push($(item).val());
			workersName[$(item).val()] = $(item).data("name");
			
		}
		selectedWorkers[currentWorker] = res;
	});
	updateNames(currentWorker);
	$('#workers').modal('hide');
}

function restoreWorkersNames(){
	for(let i=1 ; i<selectedWorkers.length; i++){
		if (selectedWorkers[i] === undefined ) continue;
		var arr=selectedWorkers[i]; 
		$("#workersNames"+i).html("");
		var names ="";
		for(let j=0 ; j<arr.length; j++){
			let item = $("#workerListItem"+arr[j]).find("input");
			if (!item.length) continue;
			names += (names.length ? ", ":"")+$(item).data("name");
			$("#workersNames"+i).append(
			"<a href='#' onClick='selectWorker("+ i +")'>"+$(item).data("name")+"</a><br>"+
			"<input type=hidden value='"+$(item).val()+"' name=workers["+i+"][]>"+
			"</div>");
		}
		if(!$("#workersNames"+i).html()){
		$("#workersNames"+i).html("<a href='#' onClick='selectWorker("+ i +")'>Исполнитель " + i + "</a>");
		}	
		
		$("#workersNames"+i).append(
		"<input type=hidden value='"+names+"' name=workersNames["+i+"]>");
	}	
}


function addWorker() {
    numWorker++;


    $('#rowHead').append("<th class='text-center'><div id='workersNames" + numWorker + "'><a href='#' onClick='selectWorker("+ numWorker +")'>Исполнитель " + numWorker + "</a></div><div style='height:40px; padding: 4px 1px 1px 1px; font-weight:normal;' id='worker"+ numWorker + "time'></div></th>");
	$("#table-operations tr.operation").each(function(index, item) {
        let operationId = $(item).attr('operation-id');
        $(item).append("<td data-worker='worker"+numWorker+"' class='text-center worker" + numWorker + "' ></td>");
        $(item).find("td").last().html($('#cell-template').html().replace(/\{checkboxid\}/g, "ch" + numWorker + operationId));
    });
	$("input[type='time']").change(function(){
		sumTime($(this).parent().parent().parent().attr('operation-id'),$(this).parent().parent().data('worker'));
	});
}

function compareTimes(begin,minBegin,end,maxEnd){
	if (begin>end)begin = [end, end = begin][0];
	if (minBegin>begin)	minBegin=begin;
	if (maxEnd<end)		maxEnd=end;
	return [maxEnd,minBegin];
}

function timeInterval(begin,end){
	if (begin>end)begin = [end, end = begin][0];
	let endArr=end.split(":");
	let beginArr=begin.split(":");
	return (Number(endArr[0])*60+Number(endArr[1]))-(Number(beginArr[0])*60+Number(beginArr[1]));
}

function sumTime(operation,worker){
	var find=false;
	var maxEnd,minBegin,minutes;
	for(var i=1;i<=numWorker;i++){
		el=$("tr[operation-id='"+operation+"']").find("td.worker"+i);
		if(el.find("input[type='checkbox']").prop("checked")){
		   if(find){
				[maxEnd,minBegin]=compareTimes(el.find("input[name='begin']").val(),minBegin,el.find("input[name='end']").val(),maxEnd);
				minutes+=timeInterval(el.find("input[name='begin']").val(),el.find("input[name='end']").val());
		   }else{	 
			   maxEnd=el.find("input[name='end']").val();
			   minBegin=el.find("input[name='begin']").val();
			   if (minBegin>maxEnd)minBegin = [maxEnd, maxEnd = minBegin][0];
			   minutes=timeInterval(minBegin,maxEnd);
			   find=true;
	       }
		}
	}
	
	if(find){
		$("tr[operation-id='"+operation+"']").find("td#time").html(minBegin+" - "+maxEnd+"<br>("+Math.floor(minutes/60)+"ч."+minutes%60+"мин)");
	}else{
		$("tr[operation-id='"+operation+"']").find("td#time").html("");
	}
	
	
	find=false;
	$("td."+worker).each(function(index,el) {
				console.log("1");
				el=$(el);
				if(el.find("input[type='checkbox']").prop("checked")){
					if(find){
						[maxEnd,minBegin]=compareTimes(el.find("input[name='begin']").val(),minBegin,el.find("input[name='end']").val(),maxEnd);
						minutes+=timeInterval(el.find("input[name='begin']").val(),el.find("input[name='end']").val());
					}else{	 
					   maxEnd=el.find("input[name='end']").val();
					   minBegin=el.find("input[name='begin']").val();
					   if (minBegin>maxEnd)minBegin = [maxEnd, maxEnd = minBegin][0];
					   minutes=timeInterval(minBegin,maxEnd);
					   find=true;
				   }
				}	
	});
	
	if(find){
		$("div #"+worker+"time").html(minBegin+" - "+maxEnd+" ("+Math.floor(minutes/60)+"ч."+minutes%60+"мин)");
	}else{
		$("div #"+worker+"time").html("");
	}
}

function removeWorker() {
    if(confirm('Удалить последнего исполнителя?')) {
        
		selectedWorkers[numWorker] = [];
		numWorker--;

        $('#rowHead th').last().remove();
        $("#table-operations tr.operation").each(function(index, item) {
            $(item).find('td').last().remove();
        });
    }
}

function changeCheckboxDone(checkbox)
{
    sumTime($(checkbox).parent().parent().parent().attr('operation-id'),$(checkbox).parent().parent().data('worker'));
	if ($(checkbox).prop('checked')) {
        $(checkbox).parent().find('label').html('Да');
		$(checkbox).parent().parent().find("div.selecttime").show();
    } else { 
        $(checkbox).parent().find('label').html('Нет');
		$(checkbox).parent().parent().find("div.selecttime").hide();
	}
}


function findIntersection(arr,begin,end){
		var error = false;
		for (var j=0; j<arr.length; j++) {
				console.log(arr[j][1]);
				console.log(arr[j][2]);

			   if(arr[j][1] < end && arr[j][2] > begin){ 
		          error =true;	
				 $("#table-operations").find("tr[operation-id='"+arr[j][0]+"']").
									find('td.worker'+arr[j][3]).addClass("table-danger");
			  }

		}

		return error;
}


function submitForm(reverse)
{ 
	var result = [];
	var error = false; 
	var error1 = "";
	var error2 = "";
	var allOperations = {};
	var resultbyworker =[];
		
	$("td.table-danger").removeClass("table-danger");
	$("div.table-danger").removeClass("table-danger");
	$("input.is-invalid").removeClass("is-invalid");
	$("tr.table-danger").removeClass("table-danger");
	var error = false;
	for (var i = 1; i <= numWorker; i++) {
		var operation = {};
		$("td.worker" + i).each(function(index1, item1) {
			var operationId = $(item1).parent().attr('operation-id');
			//allOperations[operationId]=0;
			if ($(item1).find("input[type='checkbox']").prop("checked")) {
				var begin = $(item1).find("input[name='begin']").val();
				var end = $(item1).find("input[name='end']").val();
				if (begin > end) {
					$(item1).find("input[name='end']").val(begin);
					$(item1).find("input[name='begin']").val(end);
					begin = [end, end = begin][0];
				}
				//error = (findIntersection(operation,begin,end,i,item1) || error);
				operation[operationId] = [begin,end];
				allOperations[operationId] = 1;
				result[i] = operation;
			}
		});			
	}
	
	if(reverse){
		$("#result").val(JSON.stringify(result));
		$('#form').submit();		
		return false;
	}	

	result.forEach(function(value,j) {
		if (selectedWorkers[j] === undefined  || error) return false; 
				var operation = {};
				for(var z=0; z<selectedWorkers[j].length ; z++){
					for (const [ind, time] of Object.entries(value)) {
						console.log(selectedWorkers[j][z]);
						console.log(time);
					//	console.log(resultbyworker[selectedWorkers[j][z]]);
						if(resultbyworker[selectedWorkers[j][z]]!== undefined){
							if(findIntersection(resultbyworker[selectedWorkers[j][z]],time[0],time[1])){
								//console.log(ind);
								$("#table-operations").find("tr[operation-id='"+ind+"']").
									find('td.worker'+j).addClass("table-danger");
								let item = $("#workerListItem"+selectedWorkers[j][z]).find("input");
								alert("интервалы совпадают! исполнитель " + $(item).data("name"));
								;
								error = true;
							}
						}
						if(resultbyworker[selectedWorkers[j][z]] === undefined){
							resultbyworker[selectedWorkers[j][z]] = [];
						}
						resultbyworker[selectedWorkers[j][z]].push([ind,time[0],time[1],j]);
					};
					
				}
		
	});
	
	if (error) {
		return false;
	}
	
	$("#table-operations tr.operation").each(function(index, item){
		var operationId = $(item).attr('operation-id');
		if(allOperations[operationId] != 1){
			$(item).addClass("table-danger");
			error = true;
		}
	});

	if (error) {
		alert("Укажите хотя бы одного исполнителя");
		return false;
	}
	
	console.log("res");
	console.log(result);
		
	result.forEach(function(value,index) {
		if(selectedWorkers[index]!== undefined){
			if (selectedWorkers[index].length == 0)
			{
				error2 = "Выберите сотрудника";	
				$("#workersNames"+index).addClass("table-danger");
			}
		}else{
				error2 = "Выберите сотрудника";	
				$("#workersNames"+index).addClass("table-danger");
		}

		var name = $("input[name='worker[" + index + "]']").val();
			result.forEach(function(value1, index1){
			let name1 = $("input[name='worker[" + index1 + "]']").val();
			if(name && name.toLowerCase() == name1.toLowerCase() && index != index1){
				$("input[name='worker[" + index + "]']").addClass("is-invalid");
				$("input[name='worker[" + index1 + "]']").addClass("is-invalid");
				error1 = "Имена совпадают";
			}		
		});
	});
		
	if(error2 || error1){
		alert(error2 + " " + error1);
		return false;
	}
	$("#result").val(JSON.stringify(result));
	$('#form').submit();		
}

$(document).ready(function() {
    for (let i = 0; i < <?php echo $countWorkers; ?>; i++) {
        addWorker();
    }
	var ar=[];
    <?php 
    if(!empty($cookie['workers'])) {
		$i=1;
        foreach($cookie['workers'] as $workerKey => $arr) {
			foreach($arr as $id) {
			if($workers[$id]){
			?>
			 ar.push(<?php echo $id ?>);
			<?php }}?>
		 selectedWorkers[<?php echo $workerKey ?>] = ar;
		 ar =[];
		<?php }
    }
    ?>
	restoreWorkersNames();

    <?php 
    if(!empty($times)) {
        foreach($times as $workerKey => $object) {
            if ($object) {
                foreach($object as $operationId => $operationTimes) { ?>
                    var el=$("tr[operation-id='<?php echo $operationId; ?>']").find("td.worker<?php echo $workerKey; ?>");
					el.find("input[name='begin']").val("<?php echo $operationTimes[0]; ?>");
					el.find("input[name='end']").val("<?php echo $operationTimes[1]; ?>");
					$('#ch<?php echo $workerKey; ?><?php echo $operationId; ?>').prop('checked', true);
                    changeCheckboxDone('#ch<?php echo $workerKey; ?><?php echo $operationId; ?>');
                <?php }
            }
        }
    }
    ?>

});	

</script>