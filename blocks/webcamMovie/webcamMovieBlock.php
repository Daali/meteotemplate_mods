<?php

	# 		Webcam
	# 		Namespace:		webcam
	#		Meteotemplate Block

	# 		v2.0 - Jul 13, 2016
	#			- possibility to zoom in images in a dialog popup
	#			- CSS tweaks
	# 		v3.0 - Aug 07, 2016
	#			- auto-update possible
	# 		v3.1 - Aug 07, 2016
	#			- bug fixes - ensuring compatibility on all servers
	# 		v4.0 - Mar 6, 2017
	#			- added possibility to disable opacity change
	# 			- added globe icon and position
	# 		v5.0 - Mar 7, 2017
	#			- added possibility to disable webcam location
	# 			- added possibility to set position of the location globe
	# 		v5.1 - Oct 28, 2017
	# 			- CSS tweaks
	# 		v6.0 - Nov 1, 2017
	# 			- added link to webcam plugin if installed
	# 			- optimization
	#               v1.0 - Aug 24, 2021
	#			- added video processing feed for timelapse
	#			- changed block name
        #              

	if(file_exists("settings.php")){
		include("settings.php");
	}
	else{
		echo "Please go to your control panel and go through the settings for this block first.";
		die();
	}

	$webcamURLs = explode(",",$webcamURLs);
	$webcamTitles = explode(",",$webcamTitles);
	$webcamPositions = explode(";",$webcamPositions);


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

	$color1 = $theme=="dark" ? "white" : "black";
	
?>
	<style>
		.webcamImg{
			width:95%;
			margin:0 auto;
			border-radius:10px;
			border: 1px solid #<?php echo $color_schemes[$design]['500']?>;
			margin-bottom: 10px;
			<?php 
				if($webcamBlockOpacity){
			?>
			opacity: 0.7;
			<?php 
				}
			?>
			cursor: pointer;
			z-index: 3;
		}
		.webcamImg:hover{
			opacity: 1;
		}
		.webcamMapOpener{
			cursor: pointer;
			opacity: 0.9;
			color: <?php echo $color1?>;

		}
		.webcamMapOpener:hover{
			opacity: 1;
		}

		
	</style>
	<div class="divIcon" style="top:15px;left:2%">
		<span class="fa fa-video-camera" style="font-size:1.4em"></span>
	</div>
	<br>
	<?php
		for($i=0;$i<count($webcamURLs);$i++){
			if(trim($webcamPositions[$i])=="-"){
				$showPositions = false;
			}
			else{
				$showPositions = true;
				$positions = explode(',',$webcamPositions[$i]);
			}	
	?>
			<span style="font-size:1.3em;font-weight:bold;font-variant:small-caps">
				<?php echo $webcamTitles[$i]?>
			</span><br><br>
			<div style="width:100%;position:relative">
				<video controls style="width:95%;margin:0 auto">
				<source src="<?php echo $webcamURLs[$i];?>" type="video/mp4" id="bsSrc"/>
				</video>
				<?php
					if($showPositions){
						if($mapPosition=="BR"){
							$stylePosition = "bottom:7%;right:5%;";
						}
						if($mapPosition=="BL"){
							$stylePosition = "bottom:7%;left:5%;";
						}
						if($mapPosition=="TR"){
							$stylePosition = "top:7%;right:5%;";
						}
						if($mapPosition=="TL"){
							$stylePosition = "top:7%;left:5%;";
						}
				?>
					<?php
						if($mapPosition!="OUT"){
					?>
							<div style="position:absolute;<?php echo $stylePosition?>z-index:4">
								<span class="mticon-globe webcamMapOpener" style="font-size:1.5em" id="webcam<?php echo $i?>MapOpener" data-lat=<?php echo $positions[0]?> data-lon=<?php echo $positions[1]?>></span>
							</div>
					<?php
						}
					?>
				<?php
					}
				?>
			</div>
			<?php
				if($mapPosition=="OUT"){
			?>
					<div style="width:95%;margin:0 auto;text-align:right;padding-right:3px">
						<span class="mticon-globe webcamMapOpener" style="font-size:1.5em" id="webcam<?php echo $i?>MapOpener" data-lat=<?php echo $positions[0]?> data-lon=<?php echo $positions[1]?>></span>
					</div>
			<?php 
				}
			?>
	<?php
		}
	?>
	<?php 
		if(file_exists("../../../plugins/webcam/index.php")){
	?>
			<div style="width:98%;margin:0 auto">
				<a href="<?php echo $pageURL.$path?>plugins/webcam/index.php" target="_blank"><?php echo lang('more','l')?></a>
			</div>
	<?php
		}
	?>
	<div id="webcamWindow"></div>
	<script>
		$(".webcamMapOpener").click(function(){
			lat = $(this).attr('data-lat');
			lon = $(this).attr('data-lon');
			openCameraPosition("homepage/blocks/webcam/cameraPosition.php?lat="+lat+"&lon="+lon);
		})
		setInterval(function(){ webcamBlockUpdater(); }, (<?php echo $refreshInterval?>*1000));
		function webcamBlockUpdater(){
			<?php
				for($i=0;$i<count($webcamURLs);$i++){
			?>
					newURL<?php echo $i?> = "<?php echo $webcamURLs[$i]?>";
					x = Math.round(Math.random()*10000);
					if(newURL<?php echo $i?>.includes("?")){
						newURL<?php echo $i?> = newURL<?php echo $i?> + "&randomize=" + x;
					}
					else{
						newURL<?php echo $i?> = newURL<?php echo $i?> + "?randomize=" + x;
					}

					$('#webcamImage<?php echo $i?>').attr('src', newURL<?php echo $i?>);
			<?php
				}
			?>
		}
		dialogHeight = screen.height*0.95;
		dialogWidth = screen.width*0.95;
		function zoomWebcam(imageID){
			currentSrc = $("#"+imageID).attr("src");
			$("#webcamWindow").html("<div style='width:98%;margin:0 auto;text-align:center;'><img src='"+currentSrc+"' style='height:"+(dialogHeight*0.85)+"px'></div>");
			$("#webcamWindow").dialog('open');
		}
		
		$("#webcamWindow").dialog({
			modal: true,
			autoOpen: false,
			height: dialogHeight,
			width: dialogWidth
		});
		function openCameraPosition(url){
			dialogHeight = screen.height;
			dialogWidth = screen.width;
			var $dialog = $('<div style="overflow:hidden"></div>')
				.html('<iframe style="border: 0px; " src="' + url + '&height='+dialogHeight+'" width="100%" height="100%"></iframe>')
				.dialog({
					autoOpen: false,
					modal: true,
					height: dialogHeight,
					width: dialogWidth,
					show: {
						effect: "fade",
						duration: 400
					},
					hide: {
						effect: "fade",
						duration: 800
					}
				});
			$dialog.dialog('open');
		}
	</script>
