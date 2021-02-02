<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule('iblock');
//"ID"=>97592
$filter = ["IBLOCK_ID" => 66, "ACTIVE" => "Y"];
$res=CIBlockElement::GetList(["ID"=>"ASC"], $filter, false, ['nPageSize' => 150,'iNumPage'  => $_GET['page']],[],['ID', 'NAME','PROPERTY_OLD_ID']);
while($ob1=$res->GetNextElement()){
	//echo "<pre>";print_r($ob1->GetProperties()); echo"</pre>";
	//echo "<pre>";print_r($ob1->GetFields()["ID"]); echo"</pre>";
	//echo $ob1->GetFields()["ID"]."<br>";
	$resource = CIBlockElement::GetByID($ob1->GetProperties()['OLD_ID']['VALUE']);
	if ($ob = $resource->GetNextElement())
	{
	   $arFields['PROPERTIES'] = $ob->GetProperties();
	   foreach ($arFields['PROPERTIES'] as $property)
	   {
		  $arFieldsCopy['PROPERTY_VALUES'][$property['CODE']] = $property['VALUE'];
		  if(($property['CODE'])=="документация"||($property['CODE'])=="документация"){
		  
		  }else{
		  $rsProperty = CIBlockProperty::GetList(array(),['IBLOCK_ID' => 66, "CODE" => $property['CODE']]);
			if($element = $rsProperty->Fetch())
			{	
			 //if($property['PROPERTY_TYPE']!="S"||$property['USER_TYPE']) continue;
			 if($property['PROPERTY_TYPE']=="S"&&!$property['USER_TYPE']) continue;
			 if($property['PROPERTY_TYPE']=="S"&&$property['USER_TYPE']=="HTML") continue;
			 if($property['PROPERTY_TYPE']=="N") continue;
			 if($property['PROPERTY_TYPE']=="E") continue;	
			 if($property['PROPERTY_TYPE']=="L") continue;	
			 if($property['PROPERTY_TYPE']=="F") continue;	
			 if($property['PROPERTY_TYPE']=="S"&&$property['USER_TYPE']!="DiskFile") continue;
			 	   $props[$property['PROPERTY_TYPE']."--".$property['USER_TYPE']]=1;
				   if(true){
					     if(is_array($property['VALUE'])){
							 //$arrFile=[0=>["n435364","161364"]];
						   	//echo(CIBlockElement::SetPropertyValueCode($ob1->GetFields()["ID"], "EXTERNAL_VIEW", [0=>["n435364","161364"]]));
							//CIBlockElement::SetPropertyValuesEx($ob1->GetFields()["ID"], 66, ["EXTERNAL_VIEW" => $property['VALUE']]);
							$arr=[];
							foreach ($property['VALUE'] as $file){
								foreach ($file as $file1){
									 if($file1){
									//	if(CFile::GetFileArray($file1)) {echo "1 <br>";}else{echo "0 <br>";}
									 //echo "name".$property['NAME']."==".$file1."<br>";
									 $attachedObject = \Bitrix\Disk\AttachedObject::getById($file1, array('OBJECT'));
									 $arr[]="n".$attachedObject->getObjectId();
									 }
									 
									 //echo  CFile::GetPath($file1);
									 //echo "<pre>";print_r(CFile::GetFileArray($file1)); echo"</pre>";

									 //echo(CIBlockElement::SetPropertyValueCode($ob1->GetFields()["ID"], "EXTERNAL_VIEW", $fileArr));
									 $size=$size+CFile::GetFileArray($file1)['FILE_SIZE'];
									 //$fileArr[]=CFile::GetFileArray($file1);
									 									
								}
							}
							if(strpos($property['CODE'],"_VID_")) $property['CODE']="EXTERNAL_VIEW";
							if(strpos($property['CODE'],"OKUME")) $property['CODE']="DOCUMENTATION";
							if(!empty($arr)){
								echo $property['CODE']."<pre>"; print_r($arr); echo "</pre><br>";
								//echo(CIBlockElement::SetPropertyValueCode($ob1->GetFields()["ID"], $property['CODE'], [0=>$arr]));
							}
							//print_r($fileArr);
							//CIBlockElement::SetPropertyValuesEx($ob1->GetFields()["ID"], 66, [$property['CODE'] =>[0=>$fileArr]]);
							//CIBlockElement::SetPropertyValuesEx($ob1->GetFields()["ID"], 66, [$property['CODE'] => $fileArr]);
					   }else{
						   //$size=$size+CFile::GetFileArray($property['VALUE'])['FILE_SIZE'];
							//$fileArr=CFile::GetFileArray($property['VALUE']);
							//echo(CIBlockElement::SetPropertyValueCode($ob1->GetFields()["ID"], $property['CODE'], $fileArr));
							//echo "name".$property['NAME']."<br>";
						 //echo "---".$property['VALUE']."<br>";
						 //print_R($fileArr);
					   }
				   }
				   //echo ".".$ob1->GetProperties()[$property['CODE']]['NAME'] ."--".$ob1->GetProperties()[$property['CODE']]['VALUE']."<br>";
				   //echo $element['NAME']."--".$element['VALUE']."<br>";
				   //echo $property['USER_TYPE']."<br>";
				   //echo $property['CODE']."-".$property['VALUE']."<br>";
				//if($property['VALUE']) echo "--".$ob1->GetFields()["ID"]."--".$ob->GetFields()['ID']."<br>";    
				 //  CIBlockElement::SetPropertyValuesEx($ob1->GetFields()["ID"], 66, [$property['CODE'] => $property['VALUE']]);
				//echo "<pre>";print_r($ob1->GetProperties()); echo"</pre>";
				//echo "asdasdasd-----------------------------------------";
				//echo "<pre>";print_r($ob->GetProperties()); echo"</pre>";
			}	
		  }	
	   } 
	}
}
;
echo $size;
echo "<pre>";print_r($props); echo"</pre>";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>