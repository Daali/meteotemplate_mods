<?php

	# 		Ecowitt - Modules
	# 		Namespace:		ecowittModules
	#		Meteotemplate Block

	# 		v1.0 - May 11, 2020
	# 			- initial release
	# 		v1.1 - Jan 31, 2021
	# 			- updated to include WH2650A
	# 		v1.2 - Feb 02, 2021
	# 			- updated to include WH2650A modules WH65 WH25
	# 		v1.3 - Feb 04, 2021
	# 			- updated to include WH2650A Station name and FW firmware
	# 		v1.4 - May 07, 2021
	# 			- updated to match ecowitt plugin 2.6
	# 		v1.4b - May 27, 2021
	# 			- corrected WS80, WS68 and WS65 sensor name displayed
	#       v1.4d - Aug 2, 2021
	#			- Daali hack for his system, remove battery and signal and changed PP1 to PM1
	
		
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

	if(file_exists("settings.php"))
	    {include("settings.php");}
	else
	    {
		echo "<span style='color:#".$graphColor."'>Please go to your admin section and go through the settings for this block first.</span>";
		die();
	    }


	
	if(!file_exists("../../../meteotemplateLive.txt")){
		die("<br><br>API file not found.<br><br>");
	}

	$apiData = file_get_contents("../../../meteotemplateLive.txt");
	$apiData = json_decode($apiData,true);

	//$rf_status['value'] = (110 - ($apiData['WBAT'] * 18)) ;
	//echo $apiData['WBAT'];
	//echo $rf_status['value'];
	//$lastseen = date($dateTimeFormat,$apiData['U']);
	//echo $lastseen;
	$firmware = "2.0";
	
	$data['modules'] = array (
		"WH80"    => array("module_name" => "WS80",   "battery_percent" => ($apiData['WH80BAT'] * 31), "set" => $apiData['WH80BAT'],  "last_seen" => $apiData['U'], "data_type" => array("Wind","Temperature","Humidity","Pressure","UV"), "firmware" => $firmware, "rf_status" => (117 - ($apiData['WBAT'] * 18)) ),
		"WH68"    => array("module_name" => "WS68",   "battery_percent" => ($apiData['WBAT'] * 58), "set" => $apiData['WBAT'],  "last_seen" => $apiData['U'], "data_type" => array("Wind","Sun","UV"), "firmware" => $firmware, "rf_status" => (117 - ($apiData['WBAT'] * 18)) ),
		"WH65"    => array("module_name" => "WS65",   "battery_percent" => (99-$apiData['WH65BAT'] * 25), "set" => $apiData['WH65BAT'], "last_seen" => $apiData['U'], "data_type" => array("Wind","Temperature","Humidity","Pressure","Rain","Sun","UV"), "firmware" => $firmware, "rf_status" => (117 - ($apiData['WBAT'] * 18)) ),
		"WH25"    => array("module_name" => "WH25",   "battery_percent" => (99-$apiData['WH25BAT'] * 25), "set" => $apiData['WH25BAT'], "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity","Pressure"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['WH25BAT'] * 18)) ),
		"WH40"    => array("module_name" => "WH40",   "battery_percent" => ($apiData['WBAT'] * 62),     "set" => $apiData['WBAT'],     "last_seen" => $apiData['U'], "data_type" => array("Rain"), "firmware" => $firmware, "rf_status" => (89 - ($apiData['WBAT'] * 18)) ),
		"WH31_1"  => array("module_name" => "WH31_1", "battery_percent" => (99-$apiData['T1BAT'] * 25), "set" => $apiData['T1BAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['T1BAT'] * 18)) ),
		"WH31_2"  => array("module_name" => "WH31_2", "battery_percent" => (99-$apiData['T2BAT'] * 25), "set" => $apiData['T2BAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['T2BAT'] * 18)) ),
		"WH31_3"  => array("module_name" => "WH31_3", "battery_percent" => (99-$apiData['T3BAT'] * 25), "set" => $apiData['T3BAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['T3BAT'] * 18)) ),
		"WH31_4"  => array("module_name" => "WH31_4", "battery_percent" => (99-$apiData['T4BAT'] * 25), "set" => $apiData['T4BAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['T4BAT'] * 18)) ),
		"WH31_5"  => array("module_name" => "WH31_5", "battery_percent" => (99-$apiData['T5BAT'] * 25), "set" => $apiData['T5BAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['T5BAT'] * 18)) ),
		"WH31_6"  => array("module_name" => "WH31_6", "battery_percent" => (99-$apiData['T6BAT'] * 25), "set" => $apiData['T6BAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['T6BAT'] * 18)) ),
		"WH31_7"  => array("module_name" => "WH31_7", "battery_percent" => (99-$apiData['T7BAT'] * 25), "set" => $apiData['T7BAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['T7BAT'] * 18)) ),
		"WH31_8"  => array("module_name" => "WH31_8", "battery_percent" => (99-$apiData['T8BAT'] * 25), "set" => $apiData['T8BAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['T8BAT'] * 18)) ),
		"WH32"  => array("module_name" => "WH32", "battery_percent" => (99-$apiData['TBAT'] * 25), "set" => $apiData['TBAT'],    "last_seen" => $apiData['U'], "data_type" => array("Temperature","Humidity"), "firmware" => $firmware,  "rf_status" => (117 - ($apiData['TBAT'] * 18)) ),
		"WH41_1"  => array("module_name" => "WH41_1", "battery_percent" => ($apiData['PM1BAT'] * 19),   "set" => $apiData['PM1BAT'],   "last_seen" => $apiData['U'], "data_type" => array("pp25"), "firmware" => $firmware, "rf_status" => (150 - ($apiData['PM1BAT'] * 18)) ),
		"WH41_2"  => array("module_name" => "WH41_2", "battery_percent" => ($apiData['PP2BAT'] * 19),   "set" => $apiData['PP2BAT'],   "last_seen" => $apiData['U'], "data_type" => array("pp25"), "firmware" => $firmware, "rf_status" => (150 - ($apiData['PP2BAT'] * 18)) ),
		"WH41_3"  => array("module_name" => "WH41_3", "battery_percent" => ($apiData['PP3BAT'] * 19),   "set" => $apiData['PP3BAT'],   "last_seen" => $apiData['U'], "data_type" => array("pp25"), "firmware" => $firmware, "rf_status" => (150 - ($apiData['PP3BAT'] * 18)) ),
		"WH41_4"  => array("module_name" => "WH41_4", "battery_percent" => ($apiData['PP4BAT'] * 19),   "set" => $apiData['PP4BAT'],   "last_seen" => $apiData['U'], "data_type" => array("pp25"), "firmware" => $firmware, "rf_status" => (150 - ($apiData['PP4BAT'] * 18)) ),
		"WH45"    => array("module_name" => "WH45",   "battery_percent" => ($apiData['CO2_1BAT'] * 19), "set" => $apiData['CO2_10BAT'], "last_seen" => $apiData['U'], "data_type" => array("pp25"), "firmware" => $firmware, "rf_status" => (150 - ($apiData['CO2_1BATT'] * 18)) ),
		"WH51_1"  => array("module_name" => "WH51_1", "battery_percent" => ($apiData['SM1BAT'] * 58),   "set" => $apiData['SM1BAT'],   "last_seen" => $apiData['U'], "data_type" => array("Soil"), "firmware" => $firmware, "rf_status" => (91 - ($apiData['SM1BAT'] * 18)) ),
		"WH51_2"  => array("module_name" => "WH51_2", "battery_percent" => ($apiData['SM2BAT'] * 58),   "set" => $apiData['SM2BAT'],   "last_seen" => $apiData['U'], "data_type" => array("Soil"), "firmware" => $firmware, "rf_status" => (91 - ($apiData['SM2BAT'] * 18)) ),
		"WH51_3"  => array("module_name" => "WH51_3", "battery_percent" => ($apiData['SM3BAT'] * 58),   "set" => $apiData['SM3BAT'],   "last_seen" => $apiData['U'], "data_type" => array("Soil"), "firmware" => $firmware, "rf_status" => (91 - ($apiData['SM3BAT'] * 18)) ),
		"WH51_4"  => array("module_name" => "WH51_4", "battery_percent" => ($apiData['SM4BAT'] * 58),   "set" => $apiData['SM4BAT'],   "last_seen" => $apiData['U'], "data_type" => array("Soil"), "firmware" => $firmware, "rf_status" => (91 - ($apiData['SM4BAT'] * 18)) ),
		"WH51_5"  => array("module_name" => "WH51_5", "battery_percent" => ($apiData['SM5BAT'] * 58),   "set" => $apiData['SM5BAT'],   "last_seen" => $apiData['U'], "data_type" => array("Soil"), "firmware" => $firmware, "rf_status" => (91 - ($apiData['SM5BAT'] * 18)) ),
		"WH51_6"  => array("module_name" => "WH51_6", "battery_percent" => ($apiData['SM6BAT'] * 58),   "set" => $apiData['SM6BAT'],   "last_seen" => $apiData['U'], "data_type" => array("Soil"), "firmware" => $firmware, "rf_status" => (91 - ($apiData['SM6BAT'] * 18)) ),
		"WH51_7"  => array("module_name" => "WH51_7", "battery_percent" => ($apiData['SM7BAT'] * 58),   "set" => $apiData['SM7BAT'],   "last_seen" => $apiData['U'], "data_type" => array("Soil"), "firmware" => $firmware, "rf_status" => (91 - ($apiData['SM7BAT'] * 18)) ),
		"WH51_8"  => array("module_name" => "WH51_8", "battery_percent" => ($apiData['SM8BAT'] * 58),   "set" => $apiData['SM8BAT'],   "last_seen" => $apiData['U'], "data_type" => array("Soil"), "firmware" => $firmware, "rf_status" => (91 - ($apiData['SM8BAT'] * 18)) ),
		"WH57"    => array("module_name" => "WH57",   "battery_percent" => ($apiData['LBAT'] * 19),     "set" => $apiData['LBAT'],     "last_seen" => $apiData['U'], "data_type" => array("Lightning"), "firmware" => $firmware, "rf_status" => (150 - ($apiData['LBAT'] * 18)) ),
	);

    $gwIcons = array();
	if(isset($data['modules'])){
		foreach($data['modules'] as $module){
			$thisModule = array();
			//echo $module['battery_percent'];
			if(!isset($module['set'])) {
				
			} else {
				$thisModule['name'] = $module['module_name'];
				$thisModule['battery'] = $module['battery_percent'];
				$thisModule['lastSeen'] = $module['last_seen'];
				$thisModule['parameters'] = $module['data_type'];
				$thisModule['firmware'] = $module['firmware'];
				$thisModule['connection'] = $module['rf_status']; // 90 = low, 60 = highest
				$modules[] = $thisModule;
				$gwIcons = array_merge($gwIcons, $module['data_type']);
			}
		}
	}
	$ecowitt['modules'] = $modules;
	$gwIcons = array_unique($gwIcons);
	

    $ecowitt['mainUnitName'] = $stationName;
	$ecowitt['wifi'] = transformWifi($data['modules'][$mainSensorName]['battery_percent']); // 86 = bad, 56 = good
	

	function transformWifi($signal){
		$totalPercent = 100;
		$currentPercent = $signal;
		$quality = ($currentPercent/$totalPercent)*100;
		if($quality<0){
			$quality = 0;
		}
		if($quality>100){
			$quality = 100;
		}
		if($quality<=25){
			$color = "#b20303";
		}
		if($quality>25 && $quality<=50){
			$color = "#b26603";
		}
		if($quality>50 && $quality<=75){
			$color = "#afaa00";
		}
		if($quality>75){
			$color = "#009919";
		}
		return array(round($quality),$color);
	}

	$color1 = $theme=="dark" ? "white" : "black";
	$color2 = $theme=="dark" ? $color_schemes[$design2]['900'] : $color_schemes[$design2]['100'];
	$color3 = $theme=="dark" ? $color_schemes[$design2]['300'] : $color_schemes[$design2]['600'];

	function convertIcon($parameter)
	    {
		if($parameter=="Temperature") {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-temp' style='font-size:27px'></div></td>";}
		if($parameter=="UV")        {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-uv' style='font-size:27px'></div></td>";}		
		if($parameter=="Humidity")  {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-humidity' style='font-size:27px'></div></td>";}
		if($parameter=="Pressure")  {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-pressure' style='font-size:27px'></div></td>";}
		if($parameter=="Wind")      {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-wind' style='font-size:27px'></div></td>";}
		if($parameter=="Rain")      {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-rain' style='font-size:27px'></div></td>";}
		if($parameter=="pp25")      {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-pm10' style='font-size:27px'></div></td>";}
		if($parameter=="Lightning") {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-storm' style='font-size:27px'></div></td>";}
		if($parameter=="Soil")      {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-soiltemperature' style='font-size:27px'></div></td>";}		
		if($parameter=="Sun")       {return "<td><div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-sun' style='font-size:27px'></div></td>";}		
	    }

	function convertIcon1($parameter)
	    {
		if($parameter=="Temperature") {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-temp' style='font-size:27px'></div>";}
		if($parameter=="UV")        {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-uv' style='font-size:27px'></div>";}		
		if($parameter=="Humidity")  {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-humidity' style='font-size:27px'></div>";}
		if($parameter=="Pressure")  {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-pressure' style='font-size:27px'></div>";}
		if($parameter=="Wind")      {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-wind' style='font-size:27px'></div>";}
		if($parameter=="Rain")      {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-rain' style='font-size:27px'></div>";}
		if($parameter=="pp25")      {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-pm10' style='font-size:27px'></div>";}
		if($parameter=="Lightning") {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-storm' style='font-size:27px'></div>";}
		if($parameter=="Soil")      {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-soiltemperature' style='font-size:27px'></div>";}		
		if($parameter=="Sun")       {return "<div style='width:30px;height:30px;border:3px solid ".$color1.";border-radius:50%;padding:3px'><span class='mticon-sun' style='font-size:27px'></div>";}		
	    }

	function transformBattery($level){
		if($level>=90){
			return "fa fa-battery-full";
		}
		if($level>=70 && $level<90){
			return "fa fa-battery-three-quarters";
		}
		if($level>=30 && $level<70){
			return "fa fa-battery-half";
		}
		if($level>=10 && $level<30){
			return "fa fa-battery-quarter";
		}
		if($level<10){
			return "fa fa-battery-empty";
		}
	}

	function transformSignal($signal){
		$percent = (90 - $signal)/30;
		$percent = $percent * 100;
		return round($percent);
	}

	function getBG($percent){
		if($percent<=25){
			$color = "#b20303";
		}
		if($percent>25 && $percent<=50){
			$color = "#b26603";
		}
		if($percent>50 && $percent<=75){
			$color = "#afaa00";
		}
		if($percent>75){
			$color = "#009919";
		}
		return $color;
	}
	
?>
	<span class="mticon-<?php echo $stationIcon?>" style="font-size:4em"></span><br><span style="font-variant:small-caps;font-size:1.2em;font-weight:bold"><?php echo $ecowitt['mainUnitName']?></span>
	<table style="width:98%;margin:0 auto">
		<tr>
			<td style="width:33%">
				<span class="fa fa-refresh" style="font-size:2.5em"></span><br>
			</td>
			<td style="width:33%">
				<span class="fa fa-wifi" style="font-size:2.5em"></span>
			</td>
			<td style="width:33%;vertical-align:top">
				<span class="fa fa-cogs" style="font-size:2.5em"></span><br>
			</td>
		</tr>
		<tr>
			<td style="font-size:1em">
				<?php echo date($dateTimeFormat,$apiData['U'])?>
			</td>
			<td style="font-size:1.3em">
				<div style="margin:5px;padding:3px;color:white;background:<?php echo $ecowitt['wifi'][1];?>;border:1px solid <?php echo $color1?>;border-radius:8px;font-weight:bold">
					<?php echo $ecowitt['wifi'][0]?><span style="font-size:0.7em">%</span>
				</div>
			</td>
			<td style="font-size:1em">
				<?php echo $apiData['FW'];?>
			</td>
		</tr>
		<tr>
			<td colspan="3" style="font-weight:bold;font-size:1.1em;font-variant:small-caps;text-align:left">
				<table cellspacing="4" style="margin:0 auto">
					<tr>
						<?php
						    $i = 0;
							foreach($gwIcons as $parameter)
							    {
							    $i++;
							    if ($i == 8) echo "</tr><tr>";
							    echo convertIcon($parameter);
							    }
						?>
					</tr>
				</table>
			</td>
		<tr>
	</table>
	<div id="ecowittModulesDetails" class="details">
		<span style="font-variant:small-caps;font-size:1.2em;font-weight:bold"><?php echo lang('modules','c')?></span>
		<?php 
			foreach($ecowitt['modules'] as $module){
		?>
				<div style="width:94%;border-radius:8px;padding:1%;margin:0 auto;background:#<?php echo $color2?>;border:1px solid #<?php echo $color3?>">
					<table style="width:98%;margin: 0 auto">
						<tr>
							<td colspan="2" style="font-weight:bold;font-size:1.1em;font-variant:small-caps;text-align:left;padding-left:2px">
								<?php echo $module['name'];?>
							</td>
							<td style="width:40%;vertical-align:top">
								<?php echo date($dateTimeFormat,$module['lastSeen']);?> 
							</td>

						<tr>
						<tr>
							<td colspan="2" style="font-weight:bold;font-size:1.1em;font-variant:small-caps;text-align:left">
								<table cellspacing="4">
									<tr>
										<?php 
											foreach($module['parameters'] as $parameter){
												echo convertIcon($parameter);
											}
										?> 
										<!-- <?php echo convertIcon($module['parameters'])?> -->
									</tr>
								</table>
							</td>
						<tr>
					</table>
				</div>
				<div style="width:100%;height:15px"></div>
		<?php 
			}
		?>
	</div>
	<span class="more" onclick="txt = $('#ecowittModulesDetails').is(':visible') ? '<?php echo lang('more','l')?>' : '<?php echo lang('hide','l')?>';$('#ecowittModulesDetails').slideToggle(800);$(this).text(txt)">
		<?php echo lang('more','l')?>
	</span>
