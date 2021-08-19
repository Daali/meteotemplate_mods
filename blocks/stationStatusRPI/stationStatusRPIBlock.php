<?php

	# 		Station Status RPI
	# 		Namespace:		stationStatusRPI
	#		Meteotemplate Block

	# 		v1.1 - Jan 29, 2016
	#			- added responsiveness
	# 		v2.0 - Aug 17, 2016
	#			- added last update time
	#		v3.0 - Jan 23, 2017
	#			- added outages
	#		v4.0 - Mar 23, 2017
	#			- SW type for API
	#		v5.0 - Nov 30, 2017
	#			- auto-select station icon
	# 			- optimization
	#		v5.1 - Aug 18, 2021
	#			- added Meteobridge RPI data
	#

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

	if(file_exists("settings.php")){
		include("settings.php");
	}
	else{
		echo "Please go to your admin section and go through the settings for this block first.";
		die();
	}

	$minDate = date("Y-m-d 00:00:00",strtotime("-".$outageDays." days"));

	// get last db update
	$a = mysqli_query($con,"
		SELECT DateTime
		FROM alldata
		WHERE DateTime >= '".$minDate."'
		ORDER BY DateTime
	");

	while($row = mysqli_fetch_array($a)){
		$records[] = strtotime($row['DateTime']);
		$lastDB = strtotime($row['DateTime']);
	}
	$age = time() - $lastDB;
	$maxInterval = $maxInterval * 60;

	if($age>$maxInterval){
		$stationStatusTitle = lang('offline','l');
		$stationStatus = "off";
	}
	else{
		$stationStatusTitle = lang('online','l');
		$stationStatus = "on";
	}

	$difference = dateDiff($lastDB, time());

	$outages = array();

	if(isset($records)){
		$previousRecord = $records[0];

		for($i=0;$i<count($records);$i++){
			$currentRecord = $records[$i];
			if(($currentRecord-$previousRecord)>=$maxInterval){
				$outages[] = array($previousRecord,$currentRecord,($currentRecord-$previousRecord));
			}
			$previousRecord = $currentRecord;
		}
	}

	function dateDiff($time1, $time2, $precision = 6) {
		if (!is_int($time1)) {
		  $time1 = strtotime($time1);
		}
		if (!is_int($time2)) {
		  $time2 = strtotime($time2);
		}
		if ($time1 > $time2) {
		  $ttime = $time1;
		  $time1 = $time2;
		  $time2 = $ttime;
		}
		$intervals = array('year','month','day','hour','minute','second');
		$diffs = array();
		foreach ($intervals as $interval) {
		  $ttime = strtotime('+1 ' . $interval, $time1);
		  $add = 1;
		  $looped = 0;
		  while ($time2 >= $ttime) {
			$add++;
			$ttime = strtotime("+" . $add . " " . $interval, $time1);
			$looped++;
		  }
		  $time1 = strtotime("+" . $looped . " " . $interval, $time1);
		  $diffs[$interval] = $looped;
		}
		$count = 0;
		$times = array();
		foreach ($diffs as $interval => $value) {
		  if ($count >= $precision) {
			break;
		  }
		  if ($value > 0) {
			$times[] = $value . " " . $interval;
			$count++;
		  }
		}
		$result = implode(" ", $times);
		$result = str_replace("year","y",$result);
		$result = str_replace("month","m",$result);
		$result = str_replace("day","d",$result);
		$result = str_replace("hour",lang('hAbbr','l'),$result);
		$result = str_replace("minute",lang('minAbbr','l'),$result);
		$result = str_replace("second","s",$result);
		return $result;
	}

	$swAvailable = false;
	if(file_exists("../../../meteotemplateLive.txt")){
		$apiText = json_decode(file_get_contents("../../../meteotemplateLive.txt"),true);
		if(isset($apiText['SW'])){
			$SW = $apiText['SW'];
			$swAvailable = true;
			if($SW=="meteobridge"){
				$SW = "Meteobridge";
				$RPITEMP = $apiText['RPITEMP'];
				$RPILOAD = $apiText['RPILOAD'];
				$RPIPROC = $apiText['RPIPROC'];
				$RPIDAGE = $apiText['RPIDAGE'];
			}
			else if (strpos($SW, 'weewx') !== false) {
				$SW = "WeeWx";
			}
			else if($SW=="WD"){
				$SW = "Weather Display";
			}
			else{
				$SW = ucwords($SW);
			}
		}
	}


?>
	<style>
		#stationStatusDiv{
			font-variant:small-caps;
			font-weight: bold;
			text-align: center;
			max-width: 80px;
			width: 100%;
			margin-right: auto;
			margin-left: auto;
			padding-left: 5px;
			padding-right: 5px;
			margin-bottom:10px;
			border: 1px solid white;
		}
	</style>
	<table style="width:98%;margin:0 auto">
		<tr>
			<td style="width:50%">
				<div style="width:98%;margin:0 auto;text-align:center">
					<span class="mticon-<?php echo $stationIcon?>" style="font-size:3.5em;padding-bottom:5px"></span>
				</div>
				<div id="stationStatusDiv" style="<?php if($stationStatus=="on"){ echo "background-color: #008C23;";}else{ echo "background-color: #8C0000;";}?>">
						<?php echo $stationStatusTitle?>
				</div>
				<?php 
					if($swAvailable){
				?>
						<div style="width:98%;margin:0 auto;text-align:center;font-variant:small-caps;font-weight:bold">
							<?php echo $SW?>
						</div>
				<?php
					}
				?>
			</td>
			<td>
				<div style="width:98%;margin:0 auto;font-size:0.8em;text-align:center">
					<span class="fa fa-refresh" style="font-size:2.5em"></span><br>
					<?php echo date($dateTimeFormat,$lastDB)?><br><?php echo $difference?>
				</div>
			</td>
		</tr>
		<tr>
		<td>			
						<div style="width:98%;margin:0 auto;text-align:justify;font-variant:small-caps;font-weight:bold">
							<span style="font-size:1.0em"><?php echo lang('RPI Temp: ','l')?><?php echo $RPITEMP?><br></span>
							<span style="font-size:1.0em"><?php echo lang('RPI Load: ','l')?><?php echo $RPILOAD?><br></span>
							<span style="font-size:1.0em"><?php echo lang('RPI Processes: ','l')?><?php echo $RPIPROC?><br></span>
							<span style="font-size:1.0em"><?php echo lang('RPI  Data Age: ','l')?><?php echo $RPIDAGE?><br></span>
						</div>
			</td>
		</tr>
	</table>
	<?php
		if($showOutages){
	?>
			<div id="outageDetails" class="details">
				<h2><?php echo lang('outages','c')?></h2>
				<div style="margin:0 auto;text-align:center;font-size:0.8">
					<?php
						if(count($outages)==0){
							echo "<span style='font-variant:small-caps'>".lang("no outages in the last","c")." ".$outageDays." ".lang('days','l')."</span>";
						}
						else if(count($outages)>0 && count($outages)<$maxOutages){
							echo "<span style='font-variant:small-caps'>".lang("outages in the last","c")." ".$outageDays." ".lang('days','l')."</span>";
					?>
							<br />
							<table style="width:98%;margin: 0 auto;padding-top:5px">
								<tr>
									<td style="font-variant:small-caps;font-weight:bold">
										<?php echo lang('from','c')?>
									</td>
									<td style="font-variant:small-caps;font-weight:bold">
										<?php echo lang('to','c')?>
									</td>
									<td style="font-variant:small-caps;font-weight:bold">
										<?php echo lang('duration','c')?>
									</td>
								</tr>
								<?php
									for($i=0;$i<count($outages);$i++){
								?>
										<tr>
											<td>
												<?php echo date($dateTimeFormat,$outages[$i][0])?>
											</td>
											<td>
												<?php echo date($dateTimeFormat,$outages[$i][1])?>
											</td>
											<td>
												<?php echo dateDiff($outages[$i][0],$outages[$i][1])?>
											</td>
										</tr>
								<?php
									}
								?>
							</table>
					<?php
						}
						else{
							$outageTotal = 0;
							foreach($outages as $outage){
								$outageTotal += $outage[2];
							}
							echo "<div style='width:80%;margin:0 auto'><span style='font-variant:small-caps'>".count($outages)." ".lang("outages in the last","l")." ".$outageDays." ".lang('days','l').". ".lang("total offline time","c").": ".round($outageTotal/60)." ".lang('minAbbr','l').".</span></div>";
						}
					?>
				</div>
			</div>
			<span class="more" onclick="txt = $('#outageDetails').is(':visible') ? '<?php echo lang('more','l')?>' : '<?php echo lang('hide','l')?>';$('#outageDetails').slideToggle(800);$(this).text(txt)">
				<?php echo lang('more','l')?>
			</span>
	<?php
		}
	?>
