<?php

	# 		NWS Radar Iframe
	# 		Namespace:		NWSRadarIframe
	#		Meteotemplate Block
	#
	# 		v1.0 - Jul 31, 2021
    #
    #               
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
	
	<iframe src="<?php echo $NWSPerfectURL?>" style="border:none;width:98%;height:400px;margin:0 auto"></iframe>
