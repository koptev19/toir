<?
/*
[Mark] => ФСФ
                            [ProductGUID] => 326d3508-f4ab-11dc-a392-00c0a8a85982
                            [Mark] => ФСФ
                            [Sort] => 1/2
                            [SortEn] => B/BB
                            [Depth] => 4
                            [Direction] => 
                            [Counterparty] => 
                            
*/
use Bitrix\Main,
	Bitrix\Catalog,
	Bitrix\Iblock;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
$conn_id = ftp_connect("ftp://123.ru") or die("Не удалось установить соединение с $ftp_server"); 
die();
if (file_exists('Pricebtx24.xml')) {
    $xml = simplexml_load_file('Pricebtx24.xml');
} else {
    exit('Не удалось открыть файл test.xml.');
}

$arLoadProductArray=[];
$el = new CIBlockElement();

foreach($xml as $product){
	foreach($product->attributes() as $key=>$val){
	   $attrArray[(string)$key] = (string)$val;
	}
	$attrArray['NAME']=$attrArray['Product'];
	$attrArray['NAME'].=($attrArray['Mark'])? ", ".$attrArray['Mark']:"";
	$attrArray['NAME'].=($attrArray['Sort'])? ", ".$attrArray['Sort']:"";
	$attrArray['NAME'].=($attrArray['SortEn'])? " ".$attrArray['SortEn']:"";
	$attrArray['NAME'].=($attrArray['Depth'])? ", ".$attrArray['Depth']:"";
	$attrArray['NAME'].=($attrArray['Direction'])? ", ".$attrArray['Direction']:"";
	$attrArray['PROPERTY_VALUES']=[	"Depth"=>$attrArray['Depth'],
									"Sort"=>$attrArray['Sort'],
									"Mark"=>$attrArray['Mark'],
									"Direction"=>$attrArray['Direction'],
									"SortEn"=>$attrArray['SortEn'],
								 ];

	$attr=$product->attributes();
	$GUID=$attr->ProductGUID.$attr->Sort.$attr->Depth.$attr->Direction.$attr->Mark;
	$attrArray['XML_ID']=md5($GUID);	
	$products[]=$attrArray;
	$lastid= md5($GUID);
}
//echo "<pre>";print_R($products);echo "</pre>";
$IBLOCK_ID=40;
$SECTION=105;
$arLoadProductArray=$products[1];
$tmpid=1;
foreach ($products as $k=>$arLoadProductArray){
			$arFilter = array("IBLOCK_ID" => $IBLOCK_ID);
			if (true)
			{
				/*$arLoadProductArray = array(
					"MODIFIED_BY" => $currentUserID,
					"IBLOCK_ID" => $IBLOCK_ID,
					"TMP_ID" => $tmpid
				);*/
			
				$arLoadProductArray["IBLOCK_ID"] = $IBLOCK_ID;
				//if (isset($arLoadProductArray["XML_ID"]) && '' !== $arLoadProductArray["XML_ID"])
				//{
					$arFilter["=XML_ID"] = $arLoadProductArray["XML_ID"];
			//	}
				
			}

			if (true)
			{
				$res = CIBlockElement::GetList(
					array(),
					$arFilter,
					false,
					false,
					array('ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'IBLOCK_SECTION_ID')
				);
				if ($arr = $res->Fetch())
				{
					$PRODUCT_ID = (int)$arr['ID'];
					echo "update".$k."|".$PRODUCT_ID."|".$arLoadProductArray['NAME']."<br>";
					/*if ($boolTranslitElement && $updateTranslit)
					{
						if (!isset($arLoadProductArray['CODE']) || '' === $arLoadProductArray['CODE'])
						{
							$arLoadProductArray['CODE'] = CUtil::translit($arLoadProductArray["NAME"], $TRANSLIT_LANG, $arTranslitElement);
						}
					}
					if ($bThereIsGroups)
					{
						if (!isset($currentProductSection[$PRODUCT_ID]))
							$currentProductSection[$PRODUCT_ID] = $arr['IBLOCK_SECTION_ID'];
						$LAST_GROUP_CODE_tmp = (($LAST_GROUP_CODE > 0) ? $LAST_GROUP_CODE : false);
						if (!isset($arProductGroups[$PRODUCT_ID]))
							$arProductGroups[$PRODUCT_ID] = array();
						if (!in_array($LAST_GROUP_CODE_tmp, $arProductGroups[$PRODUCT_ID]))
						{
							$arProductGroups[$PRODUCT_ID][] = $LAST_GROUP_CODE_tmp;
						}
						$arLoadProductArray["IBLOCK_SECTION"] = $arProductGroups[$PRODUCT_ID];
						$arLoadProductArray['IBLOCK_SECTION_ID'] = $currentProductSection[$PRODUCT_ID];
						$updateFacet = true;
					}*/
					$arLoadProductArray['IBLOCK_SECTION_ID'] = $SECTION ;
					$res = $el->Update($PRODUCT_ID, $arLoadProductArray, $bWorkflow, false, 'Y' === $IMAGE_RESIZE);
					echo $el->LAST_ERROR."<br>";
				}
				else
				{
					echo "add<br>";
					/*if ($bThereIsGroups)
					{
						$arLoadProductArray["IBLOCK_SECTION"] = (($LAST_GROUP_CODE>0) ? $LAST_GROUP_CODE : false);
					}
					if ($arLoadProductArray["ACTIVE"] != "N")
						$arLoadProductArray["ACTIVE"] = "Y";
					if ($boolTranslitElement)
					{
						if (!isset($arLoadProductArray['CODE']) || '' === $arLoadProductArray['CODE'])
						{
							$arLoadProductArray['CODE'] = CUtil::translit($arLoadProductArray["NAME"], $TRANSLIT_LANG, $arTranslitElement);
						}
					}*/
					$arLoadProductArray['IBLOCK_SECTION_ID'] = $SECTION ;
					$PRODUCT_ID = $el->Add($arLoadProductArray, $bWorkflow, false, 'Y' === $IMAGE_RESIZE);
					echo $el->LAST_ERROR."<br>";
					if ($bThereIsGroups)
					{
						if (!isset($arProductGroups[$PRODUCT_ID]))
							$arProductGroups[$PRODUCT_ID] = array();
						$arProductGroups[$PRODUCT_ID][] = (($LAST_GROUP_CODE > 0) ? $LAST_GROUP_CODE : false);
					}
					$res = ($PRODUCT_ID > 0);
					if ($res)
						$newProducts[$PRODUCT_ID] = true;
				}

				if (!$res)
				{
					echo $el->LAST_ERROR."<br>";
					die();
				}
			}
			
			/*foreach ($arLoadProductArray as $k=>$v){
				$PROP[250]=$arLoadProductArray['Sort'];
				$PROP[251]=$arLoadProductArray['Depth'];
				CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, $IBLOCK_ID, $PROP);
			}*/
			
			/*if ('' === $strErrorR)
			{
				$PROP = array();
				for ($i = 0; $i < $NUM_FIELDS; $i++)
				{
					if (0 == strncmp(${"field_".$i}, "IP_PROP", 7))
					{
						$cur_prop_id = intval(mb_substr(${"field_".$i}, 7));
						if (!isset($arIBlockProperty[$cur_prop_id]))
						{
							$res1 = CIBlockProperty::GetByID($cur_prop_id, $IBLOCK_ID);
							if ($arRes1 = $res1->Fetch())
								$arIBlockProperty[$cur_prop_id] = $arRes1;
							else
								$arIBlockProperty[$cur_prop_id] = array();
						}
						if (!empty($arIBlockProperty[$cur_prop_id]) && is_array($arIBlockProperty[$cur_prop_id]))
						{
							$multipleCheckId = $arRes[$i];
							if ('Y' == $CML2_LINK_IS_XML && $cur_prop_id == $arSku['SKU_PROPERTY_ID'])
							{
								$arRes[$i] = trim($arRes[$i]);
								if ('' != $arRes[$i])
								{
									if (!isset($arProductCache[$arRes[$i]]))
									{
										$rsProducts = CIBlockElement::GetList(
											array(),
											array('IBLOCK_ID' => $arSku['PRODUCT_IBLOCK_ID'], '=XML_ID' => $arRes[$i]),
											false,
											false,
											array('ID')
										);
										if ($arParentProduct = $rsProducts->Fetch())
											$arProductCache[$arRes[$i]] = $arParentProduct['ID'];
									}
									$arRes[$i] = (isset($arProductCache[$arRes[$i]]) ? $arProductCache[$arRes[$i]] : '');
								}
							}
							elseif ($arIBlockProperty[$cur_prop_id]["PROPERTY_TYPE"]=="L")
							{
								$arRes[$i] = trim($arRes[$i]);
								if ('' !== $arRes[$i])
								{
									$propValueHash = md5($arRes[$i]);
									if (!isset($arPropertyListCache[$cur_prop_id]))
									{
										$arPropertyListCache[$cur_prop_id] = array();
										$propEnumRes = CIBlockPropertyEnum::GetList(
											array('SORT' => 'ASC', 'VALUE' => 'ASC'),
											array('IBLOCK_ID' => $IBLOCK_ID, 'PROPERTY_ID' => $arIBlockProperty[$cur_prop_id]['ID'])
										);
										while ($propEnumValue = $propEnumRes->Fetch())
											$arPropertyListCache[$cur_prop_id][md5($propEnumValue['VALUE'])] = $propEnumValue['ID'];
									}
									if (!isset($arPropertyListCache[$cur_prop_id][$propValueHash]))
									{
										$arPropertyListCache[$cur_prop_id][$propValueHash] = CIBlockPropertyEnum::Add(
											array(
												"PROPERTY_ID" => $arIBlockProperty[$cur_prop_id]['ID'],
												"VALUE" => $arRes[$i],
												"TMP_ID" => $tmpid
											)
										);
									}
									if (isset($arPropertyListCache[$cur_prop_id][$propValueHash]))
									{
										$arRes[$i] = $arPropertyListCache[$cur_prop_id][$propValueHash];
									}
									else
									{
										$arRes[$i] = '';
									}
								}
							}
							elseif ($arIBlockProperty[$cur_prop_id]["PROPERTY_TYPE"]=="F")
							{
								if(preg_match("/^(ftp|ftps|http|https):\\/\\//", $arRes[$i]))
									$arRes[$i] = CFile::MakeFileArray($arRes[$i]);
								else
									$arRes[$i] = CFile::MakeFileArray($io->GetPhysicalName($_SERVER["DOCUMENT_ROOT"].$PATH2IMAGE_FILES.'/'.$arRes[$i]));

								if (!is_array($arRes[$i]) || !array_key_exists("tmp_name", $arRes[$i]))
									$arRes[$i] = '';
							}
							if (!is_array($arRes[$i]))
							{
								$arRes[$i] = trim($arRes[$i]);
								if ($arRes[$i] == '')
									$multipleCheckId = $arRes[$i];
							}

							if ($arIBlockProperty[$cur_prop_id]["MULTIPLE"]=="Y")
							{
								if (!isset($arIBlockPropertyValue[$PRODUCT_ID]))
								{
									$arIBlockPropertyValue[$PRODUCT_ID] = array();
									$multiplePropertyValuesCheck[$PRODUCT_ID] = array();
								}
								if (
									!isset($arIBlockPropertyValue[$PRODUCT_ID][$cur_prop_id])
									|| !is_array($arIBlockPropertyValue[$PRODUCT_ID][$cur_prop_id])
								)
								{
									$arIBlockPropertyValue[$PRODUCT_ID][$cur_prop_id] = array();
									$multiplePropertyValuesCheck[$PRODUCT_ID][$cur_prop_id] = array();
								}

								if (
									!in_array($multipleCheckId, $multiplePropertyValuesCheck[$PRODUCT_ID][$cur_prop_id])
								)
								{
									$arIBlockPropertyValue[$PRODUCT_ID][$cur_prop_id][] = $arRes[$i];
									$multiplePropertyValuesCheck[$PRODUCT_ID][$cur_prop_id][] = $multipleCheckId;
								}

								$PROP[$cur_prop_id] = $arIBlockPropertyValue[$PRODUCT_ID][$cur_prop_id];
							}
							else
							{
								$PROP[$cur_prop_id] = $arRes[$i];
							}
						}
					}
				}

				if (!empty($PROP))
				{
					CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, $IBLOCK_ID, $PROP);
					$updateFacet = true;
				}
			}*/
//цены


				/*	if (!empty($arFields))
					{
						if (!isset($productPriceCache[$PRODUCT_ID]))
						{
							$productPriceCache[$PRODUCT_ID] = array();
							$priceIterator = Catalog\Model\Price::getList(array(
								'select' => array('ID', 'CATALOG_GROUP_ID', 'QUANTITY_FROM', 'QUANTITY_TO'),
								'filter' => array('=PRODUCT_ID' => $PRODUCT_ID, '@CATALOG_GROUP_ID' => $priceTypeList)
							));
							while ($row = $priceIterator->fetch())
							{
								$hash = ($row['QUANTITY_FROM'] === null ? 'ZERO' : $row['QUANTITY_FROM']).'-'.
									($row['QUANTITY_TO'] === null ? 'INF' : $row['QUANTITY_TO']);
								$priceType = (int)$row['CATALOG_GROUP_ID'];
								if (!isset($productPriceCache[$PRODUCT_ID][$priceType]))
									$productPriceCache[$PRODUCT_ID][$priceType] = array();
								$productPriceCache[$PRODUCT_ID][$priceType][$hash] = (int)$row['ID'];
							}
							unset($row, $priceIterator);
						}

						foreach ($arFields as $key => $value)
						{
							$strPriceErr = '';

							$hash = ($value['QUANTITY_FROM'] === null ? 'ZERO' : $value['QUANTITY_FROM']).'-'.
								($value['QUANTITY_TO'] === null ? 'INF' : $value['QUANTITY_TO']);
							$priceType = (int)$value['CATALOG_GROUP_ID'];

							if (!isset($processedProductPriceCache[$PRODUCT_ID][$priceType][$hash]))
							{
								if (!isset($processedProductPriceCache[$PRODUCT_ID]))
									$processedProductPriceCache[$PRODUCT_ID] = array();
								if (!isset($processedProductPriceCache[$PRODUCT_ID][$priceType]))
									$processedProductPriceCache[$PRODUCT_ID][$priceType] = array();

								$priceId = (isset($productPriceCache[$PRODUCT_ID][$priceType][$hash])
									? $productPriceCache[$PRODUCT_ID][$priceType][$hash]
									: null
								);

								if ($priceId !== null)
								{
									$emptyPrice = (
										(isset($value['PRICE']) && '' === $value['PRICE']) &&
										(isset($value['CURRENCY']) && '' === $value['CURRENCY'])
									);
									$boolEraseClear = ('Y' == $CLEAR_EMPTY_PRICE ? $emptyPrice :false);
									if ($boolEraseClear)
									{
										$priceResult = Catalog\Model\Price::delete($priceId);
										if (!$priceResult->isSuccess())
										{
											$strPriceErr = implode('; ', $priceResult->getErrorMessages());
											if ($strPriceErr !== '')
												$strPriceErr = GetMessage('CATI_ERR_PRICE_DELETE').$strPriceErr;
											else
												$strPriceErr = GetMessage('CATI_ERR_PRICE_DELETE');
										}
										unset($priceResult);
									}
									else
									{
										if (!$emptyPrice)
										{
											if (isset($value['PRICE']))
												$value['PRICE'] = str_replace(array(' ', ','), array('', '.'), $value['PRICE']);
										}
										else
										{
											$value = [
												"TMP_ID" => $tmpid
											];
										}

										$priceResult = Catalog\Model\Price::update($priceId, $value);
										if ($priceResult->isSuccess())
										{
											$bUpdatePrice = 'Y';
										}
										else
										{
											$strPriceErr = implode('; ', $priceResult->getErrorMessages());
											if ($strPriceErr !== '')
												$strPriceErr = GetMessage('CATI_ERR_PRICE_UPDATE').$strPriceErr;
											else
												$strPriceErr = GetMessage('CATI_ERR_PRICE_UPDATE_UNKNOWN');
										}
										unset($priceResult);
									}
									unset($productPriceCache[$PRODUCT_ID][$priceType][$hash]);
									$processedProductPriceCache[$PRODUCT_ID][$priceType][$hash] = $priceId;
								}
								else
								{
									$boolEmptyNewPrice = (
										(isset($value['PRICE']) && '' === $value['PRICE'])
										&& (isset($value['CURRENCY']) && '' === $value['CURRENCY'])
									);
									if (!$boolEmptyNewPrice)
									{
										if (isset($value['PRICE']))
											$value['PRICE'] = str_replace(array(' ', ','), array('', '.'), $value['PRICE']);

										$priceResult = Catalog\Model\Price::add($value);
										if ($priceResult->isSuccess())
										{
											$bUpdatePrice = 'Y';
											$processedProductPriceCache[$PRODUCT_ID][$priceType][$hash] = $priceResult->getId();
										}
										else
										{
											$strPriceErr = implode('; ', $priceResult->getErrorMessages());
											if ($strPriceErr !== '')
												$strPriceErr = GetMessage('CATI_ERR_PRICE_ADD').$strPriceErr;
											else
												$strPriceErr = GetMessage('CATI_ERR_PRICE_ADD_UNKNOWN');
										}
										unset($priceResult);
									}
								}
								if ('' != $strPriceErr)
								{
									$strErrorR .= GetMessage("CATI_LINE_NO")." ".$line_num.". ".$strPriceErr.'<br>';
									break;
								}
								else
								{
									$updateFacet = true;
								}
							}
						}
					}
				}
			}*/






			$PRICE_TYPE_ID = 1;
			$currency=$arLoadProductArray["Сurrency"]=="EUR" ? "EUR": "RUB"; 
			$arFields = Array(
			    "PRODUCT_ID" => $PRODUCT_ID,
			    "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
			    "PRICE" => $arLoadProductArray["Price"],
			    "CURRENCY" => $currency,
			    //"QUANTITY_FROM" => 0,
			    //"QUANTITY_TO" => 999
			);
			$res = CPrice::GetList(array(), array("PRODUCT_ID" => $PRODUCT_ID, "CATALOG_GROUP_ID" => $PRICE_TYPE_ID));
			if ($arr = $res->Fetch()) {
			    echo "p1";
				CPrice::Update($arr["ID"], $arFields);
			} else {
				echo "p2";
				$cataloProductClass = new CCatalogProduct;
				$cataloProductClass->Add(array(
                    "ID" => $PRODUCT_ID,
                    'QUANTITY' => 1,
                ));
				$idPrice = CPrice::Add($arFields);
		    //CCatalogGroup::Update($idPrice, Array("BASE" => "Y"));
			}
}