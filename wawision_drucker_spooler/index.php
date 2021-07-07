<?php


require_once(dirname(__FILE__) . '/template.php');

// system('lpstat -a', $printers);
// $printers = explode("\n", $printers);
/*
exec("lpstat -a", $printers);
$data = array();
foreach ($printers AS $printer) {
    if (preg_match('~^(?P<name>\S+)\s*(?P<msg>.+?)$~i', $printer, $match)) {
        $data[] = array(
            'name' => $match['name'],
            'msg'  => $match['msg']
        );
    }
}

echo '<pre>';
print_r($data);
echo '</pre>';

echo '<hr><a href="javascript:window.location.reload();">RELOAD</a>';
*/
exit;


//error_reporting(E_ERROR | E_WARNING | E_PARSE);
$gedruckte = $_GET['gedruckte'];
if($gedruckte<=0)$gedruckte=0;

//Brother_DCP_9042CDN
//lpstat -p


$debug=0;

$ssl3=false;
echo "<pre>";

echo "<h4>WaWision Printer Spooler</h4>Erledigte Jobs $gedruckte \r\nDevice ".$settings["serial"]."\r\nTimestamp ".time()."\r\n";

	//Initialize handle and set options
	$username = $settings["serial"];
	$password = $settings["devicekey"];
	$auth = generateHash($password,$username);
	$url = $settings["url"]."/devices/?cmd=getJob&device=$username&auth=$auth";


	if($debug >= 1)
		echo "URL: ".$url."\r\n";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//$file_handler = fopen('somefile.log', 'a');
	//curl_setopt($ch, CURLOPT_VERBOSE, $file_handler);
	//curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
	//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_NOPROGRESS, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	if($ssl3)
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, "auth=$auth");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
	//curl_setopt($ch, CURLOPT_HEADER, 1 ); // Uncomment these for debugging


	// grab URL and pass it to the browser                                                                                                                       
	$result = curl_exec($ch);
	$xml      = simplexml_load_string( $result );
	$json     = json_encode( $xml );
	$options  = json_decode( $json, TRUE );

	//if($debug >= 2)
	//  print_r($options);

        if(!is_array($options))
          echo "<font color=red>Offline bzw. Fehlermodus</font>\r\n";
        else
          echo "<font color=green>Online - warte auf Jobs</font>\r\n";

	if(is_array($options) && !$ssl3) {
		if($debug >= 2)
			echo "Found SSL < 3\r\n";
		$ssl3=false;
	}
	else {
		if($debug >= 2)
			echo "Found SSL 3\r\n";
		$ssl3=true;
	}



	if(!isset($options['device'])) $options['device']="";

	switch($options['device'])
	{
		case "labelprinter": 
			$job = json_decode(base64_decode($options['job']),true);
			if($job['amount'] <=0) $job['amount']=1;

			$printer = new LabelPrinter();
			//$printer->ConfigureXML($xmlconfig);
			$printer->LoadXML(base64_decode($job['label']));
			$printer->Output($job['amount']);
			unset($printer);
			continue;
			break;
		case "printer":
			$job = json_decode(base64_decode($options['job']),true);

			echo $job['description'];

			if($job['amount'] <=0) $job['amount']=1;
			$tmpfilename = tempnam("/tmp/",$settings['serial']."_print");
			file_put_contents($tmpfilename,base64_decode($job['label']));
			for($i=0;$i<$job['amount'];$i++)
			{
				if(is_file($tmpfilename) && $tmpfilename!="" && filesize($tmpfilename) > 0)
                                {
                                        echo "Job $tmpfilename\r\n";
                                        //system("lpr $tmpfilename");
					$gedruckte++;
                                }
			}
			unlink($tmpfilename);
			break;
		default:
			sleep(10);
	}
echo "</pre>";
	//header("Location: index.php");
echo '<script type="text/javascript">
<!--
window.location.href = "index.php?gedruckte='.$gedruckte.'";
//–>
</script>';



function generateHash($key,$deviceid)
{ 
	$hash = "";
	for($i = 0; $i <= 200; $i++)
		$hash = sha1($hash . $key.$deviceid);
	return $hash;
}

?>

