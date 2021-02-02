<?php
$storeArray= ["Remnantsbtx24Sklad_tekhnicheskikh_materialov.xml"=>"Склад технических материалов","Remnantsbtx24TsLSh_Romanov.xml"=>"ЦЛШ Романов"];


if($_POST['SAVEFILE']){
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
	require_once($_SERVER["DOCUMENT_ROOT"]."/toir/includes/include.php");
	$results=$_POST['DATA'];
	foreach($results as $operation=>$result){
		$operation= Operation::find($operation);	
		$fd = fopen("op".$operation->ID.".xml", 'w+') or die("не удалось открыть файл");
		$str='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		$str.='<Remnants generated="2020-11-15T23:00:23">'.PHP_EOL;
		fwrite($fd, $str);
		foreach($result as $key=>$value){
			$xml = simplexml_load_file($key);
			foreach($xml->ProductItem as $product){
				$guid=$product->attributes()->GUID[0];
				if($value[(string)$guid]){
					
					$str='<ProductItem ProductCharacteristicGUID="" ProductCharacteristic="" GUID="'.$product->attributes()->GUID[0].'" Product="'.
					htmlspecialchars($product->attributes()->Product[0]).'" Quantity="'.$value[(string)$guid].'" Stock="'.$product->attributes()->Stock[0].'"/>'.PHP_EOL;
					fwrite($fd, $str);
					
					
					HighloadBlockService::add(HIGHLOAD_WRITEOFFS_BLOCK_ID, [
                        "UF_GUID" => $product->attributes()->GUID[0], 
						"UF_STORE" => $product->attributes()->Stock[0],
						"UF_NAME" => $product->attributes()->Product[0],
						"UF_QUANTITY" => $value[(string)$guid], 
                        "UF_OPERATIONID" => $operation->ID, 
                        "UF_DATE" => $operation->PLANNED_DATE, 
                        "UF_LINEID" =>$operation->LINE_ID,
						"UF_WORKSHOPID" =>$operation->WORKSHOP_ID,
						"UF_EQUIPMENTID" =>$operation->EQUIPMENT_ID,
                    ]);

				}	
			}
		}
	
	fwrite($fd, "</Remnants>");
	fclose($fd);	
	die();		
	}
}

/*if($_POST['SAVEFILE']){
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
require_once($_SERVER["DOCUMENT_ROOT"]."/toir/includes/include.php");
$results=$_POST['DATA'];	
print_r($results);
die();
foreach($results as $operation=>$result){
	$operation= Operation::find($operation);
	//$fd = fopen("test.xml", 'w+') or die("не удалось открыть файл");
	//$str='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
	//$str.='<Remnants generated="2020-11-15T23:00:23">'.PHP_EOL;
	//fwrite($fd, $str);
	foreach($result as $key=>$value){
		$xml = simplexml_load_file($key);
		print_r($xml);
		$k=1;	
		foreach($xml->ProductItem as $product){
			$guid=$product->attributes()->GUID[0];
			if($value[(string)$guid]){
				$str='<ProductItem ProductCharacteristicGUID="" ProductCharacteristic="" GUID="'.$product->attributes()->GUID[0].'" Product="'.
				htmlspecialchars($product->attributes()->Product[0]).'" Quantity="'.$value[(string)$guid].'" Stock="'.$product->attributes()->Stock[0].'"/>'.PHP_EOL;
	//			fwrite($fd, $str);

				HighloadBlockService::add(5, [
                        "UF_GUID" => $product->attributes()->GUID[0], 
						"UF_STORE" => $product->attributes()->Stock[0],
						"UF_NAME" => $product->attributes()->Product[0],
						"UF_QUANTITY" => $value[(string)$guid], 
                        "UF_OPERATIONID" => $operation->ID, 
                        "UF_DATE" => $operation->PLANNED_DATE, 
                        "UF_LINEID" =>$operation->LINE_ID,
						"UF_WORKSHOPID" =>$operation->WORKSHOP_ID,
						"UF_EQUIPMENTID" =>$operation->EQUIPMENT_ID,
                    ]);
		  }
		}
	}
	//fwrite($fd, "</Remnants>");
	//fclose($fd);	
	die();
}	
}*/


if($_POST['AJAX']){
$file= $_POST['STORE'];
//if (file_exists('Remnantsbtx24Sklad_tekhnicheskikh_materialov.xml')) {
    //$xml = simplexml_load_file('Remnantsbtx24TsLSh_Romanov.xml');
	$xml = simplexml_load_file($file);
 
  //  echo "<pre>";print_r($xml->ProductItem);echo "</pre>";
//} else {
  //  exit('Не удалось открыть файл test.xml.');
//}

$result=get_object_vars(json_decode($_POST['RESULT']));
//$store=get_object_vars($result);
//echo "<tr><td colspan=4>".$_POST['OPERATION'];print_r($result);
echo "<tr><td colspan=4>2";print_r($file);
echo "<tr><td colspan=4>3";print_r($result[$file]);
$date=date("d-m-Y",strtotime($xml->attributes()->generated[0]));
echo  "<tr id='detailhead'><th>№<th>Название<th>Остаток<p style='font-size: 11px;'>по данным 1c на ".$date." </p><th>Списать</tr>";
$k=1;
foreach($xml->ProductItem as $product){
	$guid=$product->attributes()->GUID[0];
	echo "<tr id='detailrow'>";
	echo "<td>".$k++."<input type=hidden name='guid' value='".$guid."'>";
	echo "<td id='name'>".$product->attributes()->Product[0]."<td>".$product->attributes()->Quantity[0];
	if($result[$file]->$guid){
		echo "<td><input name='quantity' value='".$result[$file]->$guid."' type=text style='width:40px'>";	
	}else{
		echo "<td onClick='showInput(this)'><div style='width:40px;height:27px; border:1px solid grey;'></div><input name='quantity' type=text style='display:none;width:40px'>";
	}
	
}

//echo json_encode($result);
die();
}

if($_POST['RESULT']){
	//echo $_POST['OPERATION'];
	$result=$_POST['DATA'];	
	//$result=get_object_vars(json_decode($_POST['DATA']))[$_POST['OPERATION']];
	print_R($result);
	print_R($result[$_POST['OPERATION']]);
	echo  "<tr id='detailhead'><th>№<th>Название<th>Списать</tr>";
	foreach($result as $key=>$value){
		$xml = simplexml_load_file($key);
		echo  "<tr><td colspan=4 align=center>".$storeArray[$key];
		$k=1;	
		foreach($xml->ProductItem as $product){
			$guid=$product->attributes()->GUID[0];
			if($value[(string)$guid]){
				echo "<tr id='detailrow'>";
				echo "<td>".$k++."<input type=hidden name='guid' value='".$guid."'>";
				echo "<td	id='name'>".$product->attributes()->Product[0];
				echo "<td>".$value[(string)$guid];	
		  }
	}
	
}

die();
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $DB;
global $USER;

use Bitrix\Main\UI\Extension;
Extension::load('ui.bootstrap4');

require_once($_SERVER["DOCUMENT_ROOT"]."/toir/includes/include.php");
?>

<table class="table table-bordered table-sm table-hover" id='table3'>
<thead>
<tr class='text-center'>
<th><div>Наименование оборудования</div></th>
<th><div>Название регламентной операции</div></th>
<th><div>Списание</div></th>
<th><div>ТМЦ</div></th>
</tr>
</thead>
<tbody>
<tr>
	<td class='table-primary text-center' colspan=100%>ЛИНИЯ тест 18.07 </td>
</tr>
<tr>
	<td>ЛИНИЯ тест 18.07 / МЕХАНИЗМ тест 17.08</td>
	<td>Операция 145</td>
	<td><a href="javascript:void(0)" onClick="showDetailList(1)">списать ТМЦ</a><br><a href="javascript:void(0)">оставить без списания</a></td>
	<td class='text-left' id="op1">
</td>
</tr>
<tr>
	<td>ЛИНИЯ тест 18.07</td>
	<td>тестовая16</td>
	<td><a href="javascript:void(0)" onClick="showDetailList(2)">списать ТМЦ</a><br><a href="javascript:void(0)">оставить без списания</a></td>
	<td class='text-left' id="op2"></td>
</tr>
<tr>
	<td>ЛИНИЯ тест 18.07</td>
	<td>1111</td>
	<td><a href="javascript:void(0)" onClick="showDetailList(3)">списать ТМЦ</a><br><a href="javascript:void(0)">оставить без списания</a></td>
	<td class='text-left' id="op3"></td>
</tr>
</tbody>
<tfoot>
<tr>
</tfoot>
</table>
</div>
<div class='mt-4 text-center'><button type="submit" class="btn btn-primary">Перейти на шаг 2</button></div>
</form> </div>
</div>
</div>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td class="bx-layout-inner-left" id="layout-left-column-bottom"></td>
<td class="bx-layout-inner-center">
<div id="footer">
<span id="copyright">
<span class="bitrix24-copyright">&copy; «Битрикс», 2020</span>
<a href="javascript:void(0)" onclick="BX.Helper.show();" class="footer-discuss-link">Поддержка24</a>
<span class="footer-link" onclick="BX.Intranet.Bitrix24.ThemePicker.Singleton.showDialog()">Темы</span>
<span class="footer-link" onclick="window.scroll(0, 0); setTimeout(function() {window.print()}, 0)">Печать</span>
</span>
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>

<div class="modal fade" tabindex="-1" id='details'>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
           <div class='modal-body'>
				<div id='find' style='padding-bottom:10px'>искать деталь<input style='margin-left: 10px;width: 230px;' id="findvalue" type=text></div>
					<table class="table table-bordered table-sm table-hover" id='detailTable'>
					<tr id='detailhead'><th>№<th>Название<th>Остаток<th>Списать</tr>
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
	var allOperations={0:1,1:1,2:1};
	var saveStep=1;
	var operation=0;
	var result="";
	var resultArray={};
	var storeArray={};
	//var saveArray=[];
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
	
	function noWriteOff(op){
		resultArray[op]={};
		$("#op"+op).html("списание не требуется");
	}
	
	function closeWindow(){
		if(saveStep==1){	
			$('#details').modal('hide');
		}else{
			//$("#detailTable tr").show();
			saveStep=1;
			getDetails($("#storeSelect").val());
			$("#close").html("Закрыть");
			//$("#detailTable").find("input[name='quantity']").prop('disabled', false);
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
			/*$("#detailTable tr#detailrow").each(function(index,el) {
				if(!$(el).find("input[name='quantity']").val()){
					$(el).hide();
				}else{
					$(el).show();
					result+=$(el).find("#name").html()+"("+$(el).find("input[name='quantity']").val()+")<br>";
					saveArray[k++]=$(el).find("input[name='guid']").val()+"/"+$(el).find("input[name='quantity']").val()
		    		$(el).find("input[name='quantity']").prop('disabled', true);
					
				}
			});	*/
			saveToArray();
			if(!checkStore()){
				alert("надо что-то списать!");
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
			//resultArray[operation]=storeArray;
			//saveFile();
			saveStep=1;
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
            url: "tmc2.php",
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
			alert("необходимо указать списание у всех операций!");
			return false;
		}		
		$.ajax({
            type: "POST",
            url: "tmc2.php",
            data: {
              	SAVEFILE:1,
				DATA:resultArray,
				OPERATION:operation	
            },
            dataType :'html',
            success: function (data) {
				var i=0;
				$('#form-step2').submit();
				console.log("sas");
				//console.log(data);
			}
         });
    }	

	function getDetails(store){
        curentStore=store;
		//console.log(JSON.stringify(resultArray));
		$.ajax({
            type: "POST",
            url: "tmc2.php",
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
		if(find.length<3){
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
	}


	function CopyObj(src) {
		 return Object.assign({}, src);
	}

	$(document).ready(function() {
		$("#find").keyup(function(){
			findString();
		});
	
	
	
	
	
	});

</script>