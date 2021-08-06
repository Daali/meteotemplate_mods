<?php

	# 		Name
	# 		Namespace:		nameSpace
	#		Meteotemplate Block

	# 		v1.0 - Jul 3, 2017
	# 			- initial release, thanks to Gianni for his help
	#		v1.1 - Aug 30, 2017
	# 			- compatibility fixes
	
		
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
	
<iframe src="https://www.rainviewer.com/map.html?loc=50.6703,4.3421,5&oFa=0&oC=0&oU=0&oCS=1&oF=0&oAP=1&rmt=4&c=1&o=83&lm=0&th=0&sm=1&sn=1" width="98%" frameborder="0" style="border:0;height:50vh;" allowfullscreen></iframe>

