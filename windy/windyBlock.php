<?php

	# 		Windy Map
	# 		Namespace:		windy
	#		Meteotemplate Block

	# 		v1.0 - Jan 10, 2018
	# 			 - initial release
	# 		v1.1 - Jan 11, 2018
	# 			 - minor bug fix
	#       v1.2 - Jul 31, 2021
	#            -added settings.php for overlay default
	#
	
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
	
	$language = loadLangs();
	
?>
	<style>
		
	</style>
	
	<iframe src="https://embed.windy.com/embed2.html?lat=<?php echo $stationLat?>&lon=<?php echo $stationLon?>&zoom=5&level=surface&overlay=<?php echo $windyOverlay?>&menu=&message=true&marker=&forecast=12&calendar=now&location=coordinates&type=map&actualGrid=&metricWind=kt&metricTemp=%C2%B0<?php echo $displayTempUnits?>" style="border:none;width:98%;height:400px;margin:0 auto"></iframe>