<?php

	# 		ifttt data alert
	# 		Namespace:		ifttt_data_alert
	#		Meteotemplate Block
		
	if(file_exists("settings.php")){
		include("settings.php");
	}
	else{
		echo "Please go to your control panel and go through the settings for this block first.";
		die();
	}
	// load theme
	$designTheme = json_decode(file_get_contents("../../css/theme.txt"),true);
	$theme = $designTheme['theme'];
	
	include("../../../config.php");
	include("../../../css/design.php");
	include("../../../scripts/functions.php");
	
	$languageRaw = file_get_contents($baseURL."lang/gb.php");
	$language['gb'] = json_decode($languageRaw,true);
	$languageRaw = file_get_contents($baseURL."lang/".$lang.".php");
	$language[$lang] = json_decode($languageRaw,true);
	
?>
	<style>
		
	</style>
<?php 
//file to check for age
$filename = "../../../cache/apiLog.txt"; 

//pulls the maker key from settings file
$apikey = $api_key;

//pulls the webhook event from settings file
$event = $event_trigger;

//pulls the stale value in minutes
$staleminutes = $stale_minutes;

//getting the last modified date of cache.txt
$LastModified = filemtime($filename); 

//setting the default value for boolean as true
$this_is_new = true; 

//test the file date vs stale date
if(time() - filemtime($filename) >= $staleminutes * 60) { 
$this_is_new = false; 

//if stale , call our webhook
$ch = curl_init(); 
$postdata = json_encode([ 
]); 
$header = array(); 
$header[] = "Content-Type: application/json"; 
curl_setopt($ch,CURLOPT_URL, "https://maker.ifttt.com/trigger/$event/with/key/$apikey"); 
curl_setopt($ch,CURLOPT_HTTPHEADER, $header); 
curl_setopt($ch,CURLOPT_POST, 1); 
curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata); 
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false); 
$result = curl_exec($ch); 
curl_close($ch); 
} 
?>