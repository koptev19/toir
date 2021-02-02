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
	<?php foreach($operationsInLine ?? [] as $lineName => $operations) {
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
            $('#writeoffs a').hide();
            $('#writeoff-operation-' + operation).show();
		}
	}

	function checkStore(){
		for (var prop in storeArray) {
		  	if (!jQuery.isEmptyObject(storeArray[prop])) {
				return true;				
			}
		} 
	return false; 	
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
	
	function saveFile(writeoffId){
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
				window.location.href = 'delayed_writeoffs.php?done=' + writeoffId;
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
	
	
	
	
	});

</script>

