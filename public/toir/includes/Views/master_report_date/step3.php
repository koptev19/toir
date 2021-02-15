<h2 class='text-center pb-4'>Отчет "План работ на день профилактики" <?php echo d($this->date); ?></h2>
<h4 class='text-center pb-4'>Шаг 3</h4>

<?
$this->view('components/select_equipment', ["multiply"=>true]);
?>

<div class=''>

<div class="table-responsive mb-3 table-thead-fixed">
<table class="table table-bordered table-sm table-hover">
    <thead>
        <tr class='text-center'>
            <th><div>Наименование оборудования</div></th>
            <th><div>Название регламентной операции</div></th>
            <th><div>Выполнено</div></th>
            <th><div>Комментарий по результату работ</div></th>
            <th><div>Время выполнения</div></th>
            <th><div>Исполнители</div></th>
			<th><div>Списать</div></th>
			<th><div>ТМЦ</div></th>
            <th><div></div></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operationsInLine as $lineName => $operations) { ?>
        <tr>
            <td class='table-success text-center' colspan=100%>
                <?php echo $lineName; ?>
            </td>
        </tr>
        <?php foreach($operations as $operation) { ?>
        <tr>
            <td width="200">
			<?php if(is_a($operation, Operation::class)) { ?>
                        <?php echo $operation->equipment ? $operation->equipment->path(false) : ''; ?>
                    <?php } else { ?>
                        <form action="" method="POST">
                            <input type="hidden" name="operation" value="<?php echo $operation->ID; ?>">
                            <input type="hidden" name="update_field" value="EQUIPMENT_ID">
                            <table with=100% class="table table-sm table-borderless"><tr><td>
                                <input type=hidden name='value' class='equipment-select-input' value="<?php echo $operation->EQUIPMENT_ID; ?>">
							</td>                            
                        	<td>
                                <button type="submit" class="btn border-0"><img src="images/check.svg"></button>
							</td></tr></table>
                        </form>
                   <?php } ?>
            <td width="200">
				<?php if(!is_a($operation, Operation::class)) { ?>
                        <form action="" method="POST">
                            <input type="hidden" name="operation" value="<?php echo $operation->ID; ?>">
                            <input type="hidden" name="update_field" value="NAME">
                            <table with=100% class="table table-sm table-borderless"><tr><td width=100%>
                                <input type="text" name="value" value="<?php echo $operation->NAME; ?>" class="form-control border-0" onfocus="$(this).parent().parent().find('button').removeClass('invisible')">
							</td>                            
                        	<td>
                                <button type="submit" class="btn border-0 invisible"><img src="images/check.svg"></button>
							</td></tr></table>
                        </form>
                    <?php } else { ?>
                        <?php echo $operation->NAME; ?>
                    <?php } ?>
			</td>
            <td class='text-center'><?php echo in_array($operation->ID, $cookie['done']) ? "Да" : 'Нет'; ?></td>
            <td style="min-width:200px;">
				<form action="" method="POST">
					<input type="hidden" name="operation" value="<?php echo $operation->ID; ?>">
					<input type="hidden" name="update_field" value="COMMENT">
					<textarea name="value" class="form-control border-0" onfocus="$(this).parent().parent().find('button').show()"><?php echo $cookie['COMMENT'][$operation->ID] ?? ''; ?></textarea>
					<button type="submit" class="btn border-0" style="display:none;"><img src="images/check.svg"></button>
               </form>
			</td>
            <td class='text-center'>
				<?php foreach($timesGrouped[$operation->ID] as $time) { ?>
                	<?php echo $time[0]." - ".$time[1];	?><br>
				<?php } ?>
            </td>
            <td width="200">
				<?php foreach($owners[$operation->ID] as $owner) { ?>
                	<?php echo $owner;	?><br>
				<?php } ?>
			</td>
			<td width="190">
				<?php if (in_array($operation->ID, $cookie['done'])) { ?>
					<a href="javascript:void(0)" onClick="showDetailList(<?php echo $operation->ID ?>)" class="btn btn-outline-primary mb-3">Списать ТМЦ</a><br>
					<a onClick="noWriteOff('<?php echo $operation->ID ?>')" href="javascript:void(0)" class="btn btn-outline-primary mb-3">Без списания</a><br>
					<a onClick="delayWriteoff('<?php echo $operation->ID ?>'); return false;" href="#" class="btn btn-outline-primary mb-3">Отложить списание</a>
				<?php } ?>
			</td>
			<td class='text-left' id="op<?php echo $operation->ID ?>"></td>
            <td>
				<?php if(!is_a($operation, Operation::class)) { ?>
                    <a href="?delete_in_session=<?php echo $operation->ID; ?>&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>"><img src='images/x.svg'></a>
                <?php } ?>
			</td>
        </tr>
		  <?php } ?>
    <?php } ?>
    </tbody>
</table>
</div>

<div class='mt-5 text-center'>
<a onClick ="saveFile(); return false" class='btn btn-success mr-5'>Всё верно. Cохранить</a>

<a href='?step=2&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>' class='btn btn-outline-secondary ml-5'>Есть ошибки. Вернуться на шаг назад</a>
<!--<button type="submit" class="btn btn-primary ml-5">Всё верно. Cохранить</button>-->
</div>
<!--</form>-->

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
	
	function saveFile(){
        if(!checkResult()){
			alert("Необходимо указать списание у всех операций!");
			return false;
		}		
		$.ajax({
            type: "POST",
            url: "tmc/tmc2.php",
            data: {
              	SAVEFILE:1,
				DATA:resultArray,
				OPERATION:operation	
            },
            dataType :'html',
            success: function (data) {
				var i=0;
				let url = '?service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>&step=4&save=1';
				for(delayedOperationId in delayedWriteoff) {
					url += "&delay_writeoff[]=" + delayedOperationId;
				}
				window.location.href = url;
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
	
		<?php 
		foreach($operationsInLine as $lineName => $operations) {
			foreach($operations as $operation) {
				if (!in_array($operation->ID, $cookie['done'])) {  
					?>
					resultArray[<?php echo $operation->ID; ?>] = {};
					<?php
				}
			} 
		}?>
	
	});

</script>

