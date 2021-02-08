<?php

$storeArray= ["Remnantsbtx24Sklad_tekhnicheskikh_materialov.xml"=>"Склад технических материалов","Remnantsbtx24TsLSh_Romanov.xml"=>"ЦЛШ Романов","Remnantsbtx24TsKF_Knyazkov.xml"=>"ЦКФ Князьков","Remnantsbtx24Birzha_syrya_Borisov.xml"=>"Биржа сырья Борисов"];

function getGuid($product){
	$guid=(string)$product->attributes()->GUID[0];
	if((string)$product->attributes()->ProductCharacteristicGUID[0]){
		$guid = $guid.md5($product->attributes()->ProductCharacteristicGUID[0]);
	}
	return $guid;
}


function sendFile($name){
	$ch = curl_init();
    $localfile = $name;
    $fp = fopen($localfile, 'r');
    curl_setopt($ch, CURLOPT_URL, 'ftp://plyterra.ru/spis/'.$localfile);
    curl_setopt($ch, CURLOPT_USERPWD, "plyterra_btx24:h8Dv2fgG");
    curl_setopt($ch, CURLOPT_UPLOAD, 1);
    curl_setopt($ch, CURLOPT_INFILE, $fp);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
    curl_exec ($ch);
}

if($_POST['GETFILES']){

	$curl = curl_init();
	foreach($storeArray as $k=>$v){
		curl_setopt($curl, CURLOPT_URL, "ftp://plyterra_btx24:h8Dv2fgG@plyterra.ru/".$k);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		echo curl_error($curl);
		if($result){
		$fh   = fopen($k, 'w');
		fwrite($fh, $result);
		fclose($fh);
		}
		

	}	
	curl_close($curl);
}



if($_POST['SAVEFILE']){
	require_once($_SERVER["DOCUMENT_ROOT"]."/toir/includes/include.php");
	$results=$_POST['DATA'];
	foreach($results as $operation=>$result){
		$operation= Operation::find($operation);	
		$fd = fopen("op".$operation->ID.".xml", 'w+') or die("не удалось открыть файл");
		$str='<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		$str.='<Remnants generated="'.date("d-m-Y\Th:m:i",time()).'">'.PHP_EOL;
		fwrite($fd, $str);
		foreach($result as $key=>$value){
			$xml = simplexml_load_file($key);
			foreach($xml->ProductItem as $product){
				$guid=getGuid($product);
				if($value[$guid]){
					
					$str='<ProductItem ProductCharacteristicGUID="'.$product->attributes()->ProductCharacteristicGUID[0].'" ProductCharacteristic="'.
					$product->attributes()->ProductCharacteristic[0].'" GUID="'.$product->attributes()->GUID[0].'" Product="'.
					htmlspecialchars($product->attributes()->Product[0]).'" Quantity="'.$value[(string)$guid].'" 
					UserName="'.UserToir::current()->fullname.'" 
					Stock="'.$product->attributes()->Stock[0].'"/>'.PHP_EOL;
					fwrite($fd, $str);
					
					
					
					
					Writeoff::create([
                        "GUID" => $product->attributes()->GUID[0], 
						"STORE" => $product->attributes()->Stock[0],
						"NAME" => $product->attributes()->Product[0],
						"UNIT" => $product->attributes()->Unit[0],
						"MOVINGDATE" => $product->attributes()->Date[0],
						"QUANTITY" => $value[(string)$guid], 
                        "OPERATION_ID" => $operation->ID, 
                        "DATE" => $operation->PLANNED_DATE, 
                        "LINE_ID" =>$operation->LINE_ID,
						"WORKSHOP_ID" =>$operation->WORKSHOP_ID,
						"EQUIPMENT_ID" =>$operation->EQUIPMENT_ID,
						"USER_ID" => UserToir::current()->id 

                    ]);

				}	
			}
		}
	
	fwrite($fd, "</Remnants>");
	fclose($fd);	
	@sendFile("op".$operation->ID.".xml");
	}
die();
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
//echo "<tr><td colspan=4>2";print_r($file);
//echo "<tr><td colspan=4>3";print_r($result[$file]);

foreach($xml->ProductItem as $k=>$product){
	//echo "<pre>";print_r($product->attributes()->Date[0]);echo "</pre>";
	//echo $product->attributes()->Date[0];
	$prod[strtotime((string)$product->attributes()->Date[0])][] = 
		[
			'guid'=>getGuid($product),
			'name'=>(string)$product->attributes()->Product[0],
			'quantity'=>(string)$product->attributes()->Quantity[0],
			'unit'=>(string)$product->attributes()->Unit[0],
			'date' => (string)$product->attributes()->Date[0],
			'Characteristic' => (string)$product->attributes()->ProductCharacteristic[0]
	];
}

krsort($prod);

$date=date("d-m-Y",strtotime($xml->attributes()->generated[0]));
echo  "<tr id='detailhead'><th>№<th>Название<th>Дата перемещения<th>Остаток<p style='font-size: 11px;'>по данным 1c на ".$date." </p><th>Списать</tr>";
$k=1;
foreach($prod as $productAr){
  foreach($productAr as $product){	
	$guid=$product['guid'];
	echo "<tr id='detailrow'>";
	echo "<td>".$k++."<input type=hidden name='guid' value='".$guid."'>";
	echo "<td id='name'>".$product['name']." ".$product['Characteristic']."<td>".$product['date']."<td>".$product['quantity']."&nbsp;".$product['unit'];
	if($result[$file]->$guid){
		echo "<td><input name='quantity' value='".$result[$file]->$guid."' type=text style='width:40px'>&nbsp;";	
	}else{
		echo "<td onClick='showInput(this)'><div style='width:40px;height:27px; border:1px solid grey;'></div><input name='quantity' type=text style='display:none;width:40px'>";
	}
  }		
}

//echo json_encode($result);
die();
}




if($_POST['RESULT']){
	//echo $_POST['OPERATION'];
	$result=$_POST['DATA'];	
	//$result=get_object_vars(json_decode($_POST['DATA']))[$_POST['OPERATION']];
	//print_R($result);
	//print_R($result[$_POST['OPERATION']]);
	echo  "<tr id='detailhead'><th>№<th>Название<th>Списать</tr>";
	foreach($result as $key=>$value){
		$xml = simplexml_load_file($key);
		echo  "<tr><td colspan=4 align=center>".$storeArray[$key];
		$k=1;	
		foreach($xml->ProductItem as $product){
			if($value[getGuid($product)]){
				echo "<tr id='detailrow'>";
				echo "<td>".$k++."<input type=hidden name='guid' value='".$guid."'>";
				echo "<td	id='name'>".$product->attributes()->Product[0] . " " .$product->attributes()->ProductCharacteristic[0];
				echo "<td>".$value[getGuid($product)]."&nbsp;".$product->attributes()->Unit[0];	
		  }
	}
	
}

die();
}



