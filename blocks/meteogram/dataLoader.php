<?php

	include("settings.php");

	$locationID = $_GET['id'];

	if (strpos($meteogramLocations, ';') !== false) {
		$meteogramLocations = explode(";",$meteogramLocations);
		foreach($meteogramLocations as $meteogramLocation){
			$meteogramIDs[] = explode(",",$meteogramLocation);
		}
	}
	else{
		$meteogramIDs[0] = explode(",",$meteogramLocations);
	}

	# read meteogram data from yr.No ( new api )
	//$yrNoLocation = 'https://api.met.no/weatherapi/locationforecast/2.0/compact?lat=43.82036&lon=13.01206&altitude=18';
	$yrNoLocation = 'https://api.met.no/weatherapi/locationforecast/2.0/compact?'.$meteogramIDs[$locationID][1];

	if(file_exists("cache/yrNo".$locationID.".txt")){ 
		if (time()-filemtime("cache/yrNo".$locationID.".txt") > 60 * 180) {
			unlink("cache/yrNo".$locationID.".txt");
		}
	}
	if(file_exists("cache/yrNo".$locationID.".txt")){
		$cached = fopen("cache/yrNo".$locationID.".txt", "r");
		$response_json_data = fread($cached,filesize("cache/yrNo".$locationID.".txt"));
		fclose($cached);
	}
	else { 
		$response_json_data = loadContent($yrNoLocation,5);
		$cached = fopen("cache/yrNo".$locationID.".txt", "w");
		fwrite($cached, $response_json_data);
		fclose($cached);
	}  

	$callback = (string)$_GET['callback'];
	if (!$callback) $callback = 'callback';
	//$json = json_decode($response_json_data,true);
	header('Content-Type: text/javascript');
	echo "$callback($response_json_data);";

	function loadContent($url,$timeout){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
		$data = curl_exec($ch);
		curl_close($ch);

		if($data==""){
			$data = file_get_contents($url);
		}

		return $data;
	}
?>