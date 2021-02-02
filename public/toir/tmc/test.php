<?php
			
/*$curl = curl_init();
$file = fopen("Pricebtx24.xml", 'w');
curl_setopt($curl, CURLOPT_URL, "ftp://plyterra.ru"); #input
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FILE, $file); #output
curl_setopt($curl, CURLOPT_USERPWD, "plyterra_btx24:h8Dv2fgG");
curl_exec($curl);
curl_close($curl);
echo curl_error($curl);
fclose($file);*/
			
/*$curl = curl_init();
$fh   = fopen("1.txt", 'w');
curl_setopt($curl, CURLOPT_URL, "ftp://plyterra_btx24:h8Dv2fgG@plyterra.ru/Remnantsbtx24Birzha_syrya_Borisov.xml");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);
echo htmlspecialchars($result); 
echo $fh;
echo $curl;
fwrite($fh, $result);
fclose($fh);
echo curl_errno ($curl);
echo curl_error($curl);
curl_close($curl);
*/
echo date("d-m-Y\Th:m:i",time());

 
 /*
    $ch = curl_init();
    $localfile = "1.txt";
    $fp = fopen($localfile, 'r');
    curl_setopt($ch, CURLOPT_URL, 'ftp://plyterra.ru/'.$localfile);
    curl_setopt($ch, CURLOPT_USERPWD, "plyterra_btx24:h8Dv2fgG");
    curl_setopt($ch, CURLOPT_UPLOAD, 1);
    curl_setopt($ch, CURLOPT_INFILE, $fp);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
    curl_exec ($ch);
    $error_no = curl_errno($ch);
    curl_close ($ch);
        if ($error_no == 0) {
            $error = 'File uploaded succesfully.';
        } else {
            $error = 'File upload error.';
        }
 
	 echo $error_no;  
*/
?>			
			
