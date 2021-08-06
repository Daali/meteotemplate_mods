<?php

	# 		Name: Radar Rainviewer 
	# 		Namespace:		radarRainviewer
	#		Meteotemplate Block

	# 		v1.0 - Aug 6, 2021
		
		
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
<iframe src="https://www.rainviewer.com/map.html?loc=<?php echo $stationLat?>,<?php echo $stationLon?>,5&oFa=0&oC=0&oU=0&oCS=1&oF=0&oAP=1&rmt=4&c=1&o=83&lm=0&th=0&sm=1&sn=1" width="98%" frameborder="0" style="border:0;height:50vh;"></iframe>

