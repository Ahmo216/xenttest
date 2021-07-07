<?php
date_default_timezone_set('Europe/Berlin');
ini_set('display_errors','on');
error_reporting(E_ALL);

if (isset($_POST['do'])) {
	$do = $_POST['do'];
} else if (isset($_GET['do'])) {
	$do = $_GET['do'];

} else if (isset($argv['1'])) {
	$do = $argv['1'];
} else {
	$params = json_decode(file_get_contents('php://input'), true);
	if ($params['do']) {
		$_POST = $params;
		$do = $_POST['do'];
	} else {
		die('Unerlaubte Anfrage');
	}

}

switch ($do) {
	case 'getPrinters':
		$return = getPrinters();
		break;
	case 'getCustomerData':
		$return = getCustomerData();
		break;
	case 'saveSettings':
		$return = saveSettings();
		break;
	case 'getJobs':
		$return = getJobs();
		break;
	case 'getDashboardStatistik':
		$return = getDashboardStatistik();
		break;
	case 'getFullStatistik':
		$return = getFullStatistik();
		break;
	case 'getChartStatistik':
		$return = getChartStatistik();
		break;
	default:
		# code...
		break;
}

echo json_encode($return);

function saveSettings() {


	unlink('settings.json');

	$json['url'] = $_POST['sendUrl'];
	$json['serial'] = $_POST['sendSerial'];
	$json['devicekey'] = $_POST['sendDevicekey'];
	$json['chartybreakperiod'] = $_POST['sendChartybreakperiod'];
	$printers = getPrinters();
	if ($printers) {
		foreach ($printers as $printer) {
			if ($printer['default']) {
				$json['defaultPrinter'] = $printer['name'];
				break;
			}
		}
	}

	$datei = fopen('settings.json', 'a+');
	fwrite($datei, json_encode($json));
	fclose($datei);

	return $json;
	
} 

function getCustomerData() {

        if(!is_file(dirname(__FILE__) . '/settings.json'))
        {
          $datei = fopen('settings.json', 'a+');
	  fclose($datei);
        }

	$data = file_get_contents(dirname(__FILE__) . '/settings.json');
	$data = json_decode($data, true);

	if (!isset($data['url']) OR $data['url'] == '') {
		$data['url'] = null;
	} 
	if (!isset($data['serial']) OR $data['serial'] == '') {
		$data['serial'] = null;
	}
	if (!isset($data['devicekey']) OR $data['devicekey'] == '') {
		$data['devicekey'] = null;
	}
	if (!isset($data['defaultPrinter']) OR $data['defaultPrinter'] == '') {
		$data['defaultPrinter'] = null;
	}

	return $data;

}

function getPrinters() {
	exec("lpstat -a", $printers);
	exec("lpstat -d", $defaultPrinter);
	$defaultPrinter = reset($defaultPrinter);
	$data = array();
	foreach ($printers AS $printer) {
	    if (preg_match('~^(?P<name>\S+)\s*(?P<msg>.+?)$~i', $printer, $match)) {

	    	$default = false;
	    	if(strpos($defaultPrinter,$match['name'])!==false) {
				$default = true;
			}

	        $data[] = array(
	            'name' => $match['name'],
	            'msg'  => $match['msg'],
	            'default' => $default,
	            'description' => $printer
	        );
	    }
	}

	
	return $data;
}

function generateHash($key,$deviceid) { 
	$hash = "";
	for($i = 0; $i <= 200; $i++)
		$hash = sha1($hash . $key.$deviceid);
	return $hash;
}

function getJobs($count = 0) {

	$customerSettings = getCustomerData();

	/*
	$username = $settings["serial"];
	$password = $settings["devicekey"];
	*/

	$auth = generateHash($customerSettings['devicekey'],$customerSettings['serial']);
	$url = $customerSettings['url'] . '/devices/?cmd=getJob&device=' . $customerSettings['serial']  . '&auth=' . $auth;

	$ssl3=false;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_NOPROGRESS, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	if($ssl3) {
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));

	// grab URL and pass it to the browser                                                                                                                       
	$result = curl_exec($ch);

	if (!$result) {
		return false;
	}

	$xml      = @simplexml_load_string( $result );
	$json     = json_encode( $xml );
	$options  = json_decode( $json, TRUE );

	$return = array();
	if(!is_array($options)) {
		$return['status'] = false;
		$return['message'] = 'Offline';
	} else {
		$return['status'] = true;
		$return['message'] = 'Warten auf Druckaufträge';
	}

	if(is_array($options) && !$ssl3) {
		$ssl3 = false;
	}
	else {
		$ssl3 = true;
	}

	if(!isset($options['device'])) {
		$options['device'] = '';
	}

	switch($options['device']) {

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

			//echo $job['description'];

			if($job['amount'] <= 0) {
				$job['amount'] = 1;
			}

			if ($job['format']) {
				switch ($job['format']) {
					case 'DINA0':
						$job['format'] = 'A0';
						break;
					case 'DINA1':
						$job['format'] = 'A1';
						break;
					case 'DINA2':
						$job['format'] = 'A2';
						break;
					case 'DINA3':
						$job['format'] = 'A3';
						break;
					case 'DINA4':
						$job['format'] = 'A4';
						break;
					case 'DINA5':
						$job['format'] = 'A5';
						break;
					case 'DINA6':
						$job['format'] = 'A6';
						break;
					default:
						$job['format'] = '';
						break;
				}
			} else {
				$job['format'] = 'A4';
			}

			$tmpfilename = tempnam("/tmp/",$customerSettings['serial']."_print");
			file_put_contents($tmpfilename,base64_decode($job['label']));

			for($i=0; $i < $job['amount']; $i++) {
				if(is_file($tmpfilename) && $tmpfilename!="" && filesize($tmpfilename) > 0) {
                                        if($job['format']=="")
					  $command = "lpr -P '" . $job['description'] . "' -r '" . $tmpfilename . "'";
                                        else
					  $command = "lpr -o fit-to-page -o media=".$job['format']." -P '" . $job['description'] . "' -r '" . $tmpfilename . "'";

					setStatistikLine($job['description'], $job['filename'] . '(' . $job['format'] . ')', $job['amount'], $command);
                    system($command);

					$return['message'] = 'Drucken';

                }
			}

			if(is_file($tmpfilename) && $tmpfilename!="" && filesize($tmpfilename) > 0) {
			unlink($tmpfilename);
			}
			break;
		default:

			//sleep(1);

			break;
	}

	return $return;

}

function getFullStatistik() {

	$data = file_get_contents(dirname(__FILE__) . '/statistik.json');
	$data = json_decode($data, true);

	if (isset($data['all']['jobs'])) {

		krsort($data['all']['jobs']);

		$return['all']['anzahlJobs'] = $data['all']['anzahlJobs'];

		foreach ($data['all']['jobs'] as $jobTime => $job) {
			$return['all']['jobs'][] = $job;
		}

		if (count($data['all']['jobs']) >= 100) {
			array_shift($data['all']['jobs']);
		}

	} 

	if (isset($data['drucker'])) {

		foreach ($data['drucker'] as $drucker => $druckerData) {

			$return['drucker'][$drucker] = array();
			$return['drucker'][$drucker]['anzahlJobs'] = $druckerData['anzahlJobs'];
			$return['drucker'][$drucker]['jobs'] = array();

			if (isset($druckerData['jobs'])) {
				krsort($druckerData['jobs']);
				foreach ($druckerData['jobs'] as $jobTimeB => $job) {
					$return['drucker'][$drucker]['jobs'][] = $job;
					if (count($data['drucker'][$drucker]['jobs']) >= 100) {
						array_shift($data['drucker'][$drucker]['jobs']);
					}
				}
			}

			if (count($data['drucker'][$drucker]) >= 100) {
				array_shift($data['drucker'][$drucker]);
			}
		}
	}

	$datei = fopen('statistik.json', 'w+');
	fwrite($datei, json_encode($data));
	fclose($datei);

	return $return;
}

function getDashboardStatistik() {

	$data =  getFullStatistik();

	if (isset($data['all']['jobs'])) {
		$jobCount = 0;
		foreach($data['all']['jobs'] as $jobKey => $job) {
			$jobCount++;
			if ($jobCount >= 8) {
				unset($data['all']['jobs'][$jobKey]);
			}
		}
	}


	return $data;

}

function getChartStatistik() {

	$data = getFullStatistik();
	$chart = array();
	$return = array();

	if ($data['drucker']) {
		foreach ($data['drucker'] as $drucker => $druckerData) {
			foreach ($druckerData['jobs'] as $key => $job) {

				if (!array_key_exists($drucker, $chart)) {
					$chart[$drucker] = array();
				}

				if (!array_key_exists(strtotime(date('d.m.Y', $job['time'])), $chart[$drucker])) {
					$chart[$drucker][strtotime(date('d.m.Y', $job['time']))] = 0;
				}
				$chart[$drucker][strtotime(date('d.m.Y', $job['time']))]++;
			}
			ksort($chart[$drucker]);
		} 
	}

	if ($chart) {
		foreach ($chart as $drucker => $druckerData) {
			foreach ($druckerData as $datum => $value) {
				$return['drucker'][$drucker]['dataset'][] = $value;
				$return['drucker'][$drucker]['labels'][] = date('d.m.Y', $datum);
			}
		}
	}

	return $return;

}

function setStatistikLine($drucker, $tmpfilename, $amount, $command) {

	$data = file_get_contents(dirname(__FILE__) . '/statistik.json');
	$data = json_decode($data, true);

	// Alle
	
	if (!isset($data['all'])) {
		$data['all'] = array();
	} 

	if (!isset($data['all']['anzahlJobs'])) {
		$data['all']['anzahlJobs'] = 0;
	}

	if (!isset($data['all']['jobs'])) {
		$data['all']['jobs'] = array();
	}

	$data['all']['anzahlJobs']++;
	$data['all']['jobs'][time()] = array(
		'drucker' => $drucker,
		'tmpfilename' => $tmpfilename,
		'amount' => $amount,
		'command' => $command,
		'time' => time()
	);

	// Nach Drucker

	if (!isset($data['drucker'])) {
		$data['drucker'] = array();
	}

	if (!array_key_exists($drucker, $data['drucker'])) {
		$data['drucker'][$drucker] = array();
	}

	if (!isset($data['drucker'][$drucker]['anzahlJobs'])) {
		$data['drucker'][$drucker]['anzahlJobs'] = 0;
	}

	if (!isset($data['drucker'][$drucker]['jobs'])) {
		$data['drucker'][$drucker]['jobs'] = array();
	}


	$data['drucker'][$drucker]['anzahlJobs']++;
	$data['drucker'][$drucker]['jobs'][time()] = array(
		'drucker' => $drucker,
		'tmpfilename' => $tmpfilename,
		'amount' => $amount,
		'command' => $command,
		'time' => time()
	);

	$datei = fopen('statistik.json', 'w+');
	fwrite($datei, json_encode($data));
	fclose($datei);

}








