<?php

	# 		Meteogram block
	# 		Namespace:		meteogram
	#		Meteotemplate Block
	
	#		v1.1 - Apr 8, 2016
	#			- changes for version 6.0
	#		v1.2 - Oct 5, 2016
	#			- bug fixes
	# 		v1.3 - Feb 3, 2017
	# 			- implemented caching
	# 			- fixed wind arrow color for light theme
	# 		v2.0 - Apr 29, 2017
	# 			- added support for multiple locations
	# 		v3.0 - May 3, 2017
	# 			- optimization, removed iframe
	# 			- CSS tweaks
	# 		v3.1 - Jan 8, 2018
	# 			- bug fixes
	# 		v3.2 - Apr 10, 2021 (davidefa)
	# 			- added support for yr.no api 2.0
	# 			- corrected precipitation and icons rapresentation
	# 		v3.2c - Apr 12, 2021 (davidefa)
	# 			- restored wind speed indication in arrow
	# 		v3.3 - Apr 12, 2021 (davidefa)
	# 			- added wind spline ( with superimposed wind direction arrow )
	# 		v3.3a - Apr 13, 2021 (davidefa)
	# 			- time expressed in local time ( it was zulu time )
	# 		v3.3b - Jun 02, 2021 (davidefa)
	# 			- added separation between temperature and wind graphs


	// load theme
	$designTheme = json_decode(file_get_contents("../../css/theme.txt"),true);
	$theme = $designTheme['theme'];
	
	include("../../../config.php");
	include("../../../css/design.php");
	include("../../../scripts/functions.php");
	
	$language = loadLangs();

	if(file_exists("settings.php")){
		include("settings.php");
	}
	else{
		echo "Please go to your admin section and go through the settings for this block first.";
		die();
	}

    $meteogramLocations0 = $meteogramLocations;
	$meteogramLocations = explode(";",$meteogramLocations);
	foreach($meteogramLocations as $meteogramLocation){
		$meteogramIDs[] = explode(",",$meteogramLocation);
	}

	if(!is_dir("cache")){
		mkdir("cache");
	}
	$tempChartHeight = 100-($windChartHeight+$separation);
	$tempChartPercent = ($tempChartHeight+$separation)/100;

	$stationTimezone = new DateTimeZone($stationTZ);
	$stationOffsetSec  = $stationTimezone->getOffset(new DateTime);
?>
	<style>
		
	</style>
	<?php 
		if(count($meteogramLocations)>1){
	?>
			<select class="button2" id="idLocationMeteogram" style="margin-bottom:5px">
				<?php 
					foreach($meteogramIDs as $key=>$meteogramID){
				?>
						<option value=<?php echo $key?> <?php if($key==0){echo "selected";}?>><?php echo $meteogramID[0]?></option>
				<?php 
					}
				?>
			</select>
	<?php 
		}
		else{
	?>
			<input type="hidden" id="idLocation" value="0">
	<?php
		}
	?>
	<div id="containerMeteogram" style="width: 98%; height: 310px; margin: 0 auto">
		<div style="margin-top: 100px; text-align: center" id="loading">
			<i class="fa fa-spinner fa-spin"></i> Loading data from external source
		</div>
	</div>
	<div style='width:98%;margin:0 auto;text-align:center;font-size:0.7em;font-variant:small-caps;padding-top:10px'><?php echo lang('data source','c')?>: <a href="http://yr.no" target="_blank">yr.no</div></a>	<script>
		Highcharts.setOptions({
			global: {
				useUTC: false, 
				timezoneOffset: <?php echo $offset*-60?> 
			},
			lang: {
				months: ['<?php echo lang('january','c')?>', '<?php echo lang('february','c')?>', '<?php echo lang('march','c')?>', '<?php echo lang('april','c')?>', '<?php echo lang('may','c')?>', '<?php echo lang('june','c')?>', '<?php echo lang('july','c')?>', '<?php echo lang('august','c')?>', '<?php echo lang('september','c')?>', '<?php echo lang('october','c')?>', '<?php echo lang('november','c')?>', '<?php echo lang('december','c')?>'],
				shortMonths: ['<?php echo lang('janAbbr','c')?>', '<?php echo lang('febAbbr','c')?>', '<?php echo lang('marAbbr','c')?>', '<?php echo lang('aprAbbr','c')?>', '<?php echo lang('mayAbbr','c')?>', '<?php echo lang('junAbbr','c')?>', '<?php echo lang('julAbbr','c')?>', '<?php echo lang('augAbbr','c')?>', '<?php echo lang('sepAbbr','c')?>', '<?php echo lang('octAbbr','c')?>', '<?php echo lang('novAbbr','c')?>', '<?php echo lang('decAbbr','c')?>'],
				weekdays: ['<?php echo lang('sundayAbbr','c')?>', '<?php echo lang('mondayAbbr','c')?>', '<?php echo lang('tuesdayAbbr','c')?>', '<?php echo lang('wednesdayAbbr','c')?>', '<?php echo lang('thursdayAbbr','c')?>', '<?php echo lang('fridayAbbr','c')?>', '<?php echo lang('saturdayAbbr','c')?>'],
				resetZoom: ['<?php echo lang('default zoom','c')?>']
			}		
		});
	</script>
	<?php include("../../../css/highcharts.php");?>
		<script>

			function Meteogram(xml, container) {
				// Parallel arrays for the chart data, these are populated as the XML/JSON file
				// is loaded
				this.date = [];
				this.symbols = [];
				this.precipitations = [];
				this.windDirections = [];
				this.windDirectionNames = [];
				this.windSpeed = [];
				this.windSpeeds = [];
				this.windSpeedNames = [];
				this.temperatures = [];
				this.pressures = [];

				// Initialize
				this.xml = xml;
				this.container = container;

				// Run
				this.parseYrData();
			}

			Meteogram.prototype.smoothLine = function (data) {
				var i = data.length,
					sum,
					value;

				while (i--) {
					data[i].value = value = data[i].y;
					sum = (data[i - 1] || data[i]).y + value + (data[i + 1] || data[i]).y;
					data[i].y = Math.max(value - 0.5, Math.min(sum / 3, value + 0.5));
				}
			};

			Meteogram.prototype.tooltipFormatter = function (tooltip) {
				var index = tooltip.points[0].point.index,
					ret = '<small>' + Highcharts.dateFormat('%A, %b %e, %H:%M', tooltip.x) + '-' +
						Highcharts.dateFormat('%H:%M', tooltip.points[0].point.to) + '</small><br>';

				ret += '<table>';
				Highcharts.each(tooltip.points, function (point) {
					var series = point.series;
					ret += '<tr><td style="text-align:left"><span style="color:' + series.color + '">\u25CF</span> ' + series.name +
						': </td><td style="white-space:nowrap;style="text-align:left">' + Highcharts.pick(point.point.value, point.y) +
						series.options.tooltip.valueSuffix + '</td></tr>';
					});
				ret += '<tr><td style="vertical-align: top;text-align:left"> </td><td style="white-space:nowrap;text-align:left"> &nbsp;' + Highcharts.numberFormat(this.windDirections[index], 1) + ' ??</td></tr>';
				ret += '</table>';
				return ret;
			};

			Meteogram.prototype.drawWeatherSymbols = function (chart) {
				var meteogram = this;

				$.each(chart.series[0].data, function (i, point) {
					var sprite,
					    group;

                    //console.info("i->",i," resol=",meteogram.resolution);
					//if (meteogram.resolution > 36e5 || i % 2 === 0)
					if ( (i == 0) || ((meteogram.date[i] - meteogram.date[i-1]) > 36e5) || ((i % 2) == 0) )
					   {
					   //sprite = 'https://api.met.no/images/weathericons/png/'+meteogram.symbols[i].trim()+'.png';
					   sprite = '<?php echo $pageURL.$path."homepage/blocks/meteogram/imgs/" ?>'+meteogram.symbols[i].trim()+'.png';
    	   			   if (sprite)
  			   	<?php if ($iconPosition == 'top'){ ?>
                       	 {
                         //console.info("i->",i," sprite=",sprite);
			    		 chart.renderer.image(sprite, point.plotX + chart.plotLeft - 15, chart.plotTop, 30, 30).add();
			    		 }  // if sprite
                <?php }else{ ?>
                         {
                         //console.info("i->",i," sprite=",sprite);
			    		 chart.renderer.image(sprite, point.plotX + chart.plotLeft - 15, point.plotY + chart.plotTop - 30, 30, 30).attr({zIndex: 3}).add();
			    		 }  // if sprite
                <?php } ?>
					   }  // if meteogram.resolution
					});     // each chart.series
				};    // drawWeathersymbols

			Meteogram.prototype.windArrow = function (name) {
				var level,
					path;
				path = [
					'M', 0, 7, // base of arrow
					'L', -1.5, 7,
					0, 10,
					1.5, 7,
					0, 7,
					0, -6 // top
				];
				level = $.inArray(name, ['Calm', 'Light air', 'Light breeze', 'Gentle breeze', 'Moderate breeze','Fresh breeze', 'Strong breeze', 'Near gale', 'Gale', 'Strong gale', 'Storm', 'Violent storm', 'Hurricane']);

				if (level === 0) {
					path = [];
				}

				if (level === 2) {
					path.push('M', 0, -8, 'L', 4, -8); // short line
				} else if (level >= 3) {
					path.push(0, -10, 7, -10); // long line
				}

				if (level === 4) {
					path.push('M', 0, -7, 'L', 4, -7);
				} else if (level >= 5) {
					path.push('M', 0, -7, 'L', 7, -7);
				}

				if (level === 5) {
					path.push('M', 0, -4, 'L', 4, -4);
				} else if (level >= 6) {
					path.push('M', 0, -4, 'L', 7, -4);
				}

				if (level === 7) {
					path.push('M', 0, -1, 'L', 4, -1);
				} else if (level >= 8) {
					path.push('M', 0, -1, 'L', 7, -1);
				}

				return path;
			};
			Meteogram.prototype.drawWindArrows = function (chart) {
				var meteogram = this;

				$.each(chart.series[3].data, function (i, point) {
					var arrow, x, y;

					//if (meteogram.resolution > 36e5 || i % 2 === 0) {
					if ( (i == 0) || ((meteogram.date[i] - meteogram.date[i-1]) > 36e5) || ((i % 2) == 0) ) {
						x = point.plotX + chart.plotLeft ;
						y = chart.plotTop + chart.plotHeight * <?php echo $tempChartPercent?> + point.plotY;
                		//console.info("bot=",chart.plotTop + chart.plotHeight, "py=",point.plotY, "y=",y);
						if (meteogram.windSpeedNames[i] === '<?php echo lang('beaufort0','c')?>') {
							arrow = chart.renderer.circle(x, y, 10).attr({
								fill: 'none'
							});
						} else {
							arrow = chart.renderer.path(
								meteogram.windArrow(meteogram.windSpeedNames[i])
							).attr({
								rotation: parseInt(meteogram.windDirections[i], 10),
								translateX: x, // rotation center
								translateY: y // rotation center
							});
						}
						arrow.attr({
							stroke: '<?php echo $theme=="dark" ? "white" : "black"?>',
							'stroke-width': 1.5,
							zIndex: 5,
						})
						.add();

					}
				});
			};
			Meteogram.prototype.drawBlocksForWindArrows = function (chart) {
				var xAxis = chart.xAxis[0],
					x,
					pos,
					max,
					isLong,
					isLast,
					i;

				//for (pos = xAxis.min, max = xAxis.max, i = 0; pos <= max + 36e5; pos += 36e5, i += 1) {

					// Get the X position
					pos = xAxis.max + 36e5;
					x = Math.round(xAxis.toPixels(pos)) + 0.5;
                    console.info("x->",x," top=",chart.plotTop," height=",chart.plotHeight);

					chart.renderer.path(['M', x, chart.plotTop,
						'L', x, chart.plotTop + chart.plotHeight])
						.attr({
							'stroke': chart.options.chart.plotBorderColor,
							'stroke-width': 1
						})
						.add();
				//}
			};

			Meteogram.prototype.getTitle = function () {
				//return this.xml.location.name + ', ' + this.xml.location.country + '<br>';
				idLocation = $("#idLocationMeteogram").val();
				if (!idLocation) idLocation = 0;
				meteogramLocations = "<?php echo $meteogramLocations0 ?>";
                meteogramLocation = meteogramLocations.split(";");
                //console.info("ml=",meteogramLocation);
                for (i=0; i<meteogramLocation.length; i++)
                	{location[i] = meteogramLocation[i].split(',');}
				return location[idLocation][0]+'<br>';
				};

			<?php
				if($theme=="dark"){
					$innerGraph = "#fff";
				}
				else{
					$innerGraph = "#000";
				}
			?>

			Meteogram.prototype.getChartOptions = function () {
				var meteogram = this;

				return {
					chart: {
						renderTo: this.container,
						marginBottom: 70,
						marginRight: 40,
						marginTop: 50,
						plotBorderWidth:0,
						alignTicks: false,
						backgroundColor: "none",
					},

					title: {
						text: this.getTitle(),
						align: 'center',
						style: {
							color: "<?php echo $innerGraph?>",
						}
					},

					tooltip: {
                                                enabled: true,
						shared: true,
						useHTML: true,
						formatter: function () {
							return meteogram.tooltipFormatter(this);
						}
					},

					xAxis: [{ // Bottom X axis
						type: 'datetime',
						style: {
							color: "<?php echo $innerGraph?>",
						},
                <?php if ($resolution == '1h'){ ?>
						tickInterval: 2 * 36e5, // two hours
						minorTickInterval: 1 * 36e5, // one hour
                <?php }else{ ?>
						tickInterval: 6 * 36e5, // six hours
						minorTickInterval: 2 * 36e5, // two hour
                <?php } ?>
						tickLength:1,
						gridLineWidth: 0,
						gridLineColor: '<?php echo $innerGraph?>',
						startOnTick: false,
						endOnTick: false,
						minPadding: 0,
						maxPadding: 0,
						lineColor: '<?php echo $innerGraph?>',
						minorGridLineColor: '#888888',
						tickColor: '<?php echo $innerGraph?>',
						//offset: 30,
						showLastLabel: true,
						labels: {
							format: '{value:%H}',
							style: {
								color: "<?php echo $innerGraph?>",
							},
						}
					}, { // Top X axis
						linkedTo: 0,
						type: 'datetime',
						tickInterval: 24 * 3600 * 1000,
						labels: {
							format: '{value:<span style="font-size: 12px; font-weight: bold">%a</span> %b %e}',
							align: 'left',
							x: 3,
							y: -5,
							style: {
								color: "<?php echo $innerGraph?>",
							},
						},
						opposite: true,
						tickLength: 20,
						gridLineWidth: 0,
						gridLineColor: '<?php echo $innerGraph?>',
					}],

					yAxis: [{ // temperature axis 0
						title: {
                          	   text: '??<?php echo $displayTempUnits?>',
						       },
						labels: {
							format: '{value}',
							style: {color: "<?php echo $innerGraph?>"},
							x: -16
						},
						plotLines: [{ // zero plane
							value: 0,
							color: '<?php echo $innerGraph?>',
							width: 0,
							zIndex: 2,
						}],
						// Custom positioner to provide even temperature ticks from top down
						tickPositioner: function () 
						    {
							var max = Math.ceil(this.max) + 1,
								pos = max - 12, // start
								ret;

							if (pos < this.min) 
							    {
								ret = [];
								while (pos <= max) 
									{ret.push(pos += 1);}
							    } // else return undefined and go auto

							return ret;
						    },
						startOnTick: false,
      					offset: 0,
      					height: '<?php echo $tempChartHeight?>%',
						maxPadding: 0.3,
						tickInterval: 1,
						gridLineColor: '<?php echo $innerGraph?>'

					}, { // precipitation axis 1
						title: {text: ''},
						labels: {enabled: false},
						gridLineWidth: 0,
						tickLength: 0,
      					offset: 0,
						opposite: true,
      					height: '<?php echo $tempChartHeight?>%'

					}, { // pressure axis 2
						allowDecimals: false,
						title: {
							text: '<?php echo unitFormatter($displayPressUnits)?>',
							offset: 0,
							align: 'high',
							rotation: 0,
							style: {
								fontSize: '1em',
								color:  '<?php echo $innerGraph?>',
							},
							textAlign: 'left',
							x: 13
						},
						labels: {
							style: {
								fontSize: '1em',
								color:  '<?php echo $innerGraph?>',
							},
							y: 2,
							x: 13
						},
      					height: '<?php echo $tempChartHeight?>%',
						gridLineWidth: 0,
      					offset: 0,
						opposite: true,
						showLastLabel: false,
						gridLineColor: '<?php echo $innerGraph?>'

					}, { // wind axis 3
						title: {text: '<?php echo unitFormatter($displayWindUnits)?>'},
						labels: {enabled: true},
						//gridLineWidth: 0,
						//tickLength: 0,
						tickAmount: 3,
						endOnTick: false,
      					offset: 0,
      					top: '<?php echo $tempChartHeight+$separation?>%',
      					height: '<?php echo $windChartHeight?>%'

					}],

					legend: {
						itemMarginBottom:-20,
						margin:40,
						style: {
							color: "<?php echo $innerGraph?>",
						},
					},

					credits: {
						enabled: false
					},

					plotOptions: {
						series: {
							pointPlacement: 'between'
						}
					},


					series: [{ // temperature
						name: '<?php echo lang('temperature','c')?>',
						data: this.temperatures,
						type: 'spline',
						marker: {
							enabled: false,
							states: {
								hover: {
									enabled: true
								}
							}
						},
						tooltip: { valueSuffix: '??<?php echo $displayTempUnits?>' },
						zIndex: 1,
						color: '<?php echo $innerGraph?>',
						negativeColor: '<?php echo $innerGraph?>'
					}, {    // precipitation
						name: '<?php echo lang('precipitation','c')?>',
						data: this.precipitations,
						type: 'column',
						color: '#<?php echo $color_schemes[$design2]['400']?>',
						yAxis: 1,
						groupPadding: 0,
						pointPadding: 0,
						borderWidth: 0,
						shadow: false,
						dataLabels: {
							enabled: true,
							formatter: function () {
								if (this.y > 0) {
									return this.y;
								}
							},
							style: {fontSize: '1em'}
						},
						tooltip: { valueSuffix: '<?php echo $displayRainUnits?>' }
					}, {    // pressure
						name: '<?php echo lang('pressure','c')?>',
						color:  '#<?php echo $color_schemes[$design2]['200']?>',
						data: this.pressures,
						marker: {enabled: false},
						shadow: false,
						tooltip: {valueSuffix: ' <?php echo $displayPressUnits?>'},
						dashStyle: 'shortdot',
						yAxis: 2
					}, {    // wind
						name: '<?php echo lang('wind','c')?>',
						color:  '#<?php echo $color_schemes[$design2]['600']?>',
						type: 'spline',
						data: this.windSpeed,
						marker: {enabled: false},
						shadow: false,
						tooltip: {enabled: false, valueSuffix: ' <?php echo $displayWindUnits?>'},
						yAxis: 3
					}]
				};
			};

			Meteogram.prototype.onChartLoad = function (chart) {

				this.drawWeatherSymbols(chart);
				this.drawWindArrows(chart);
				this.drawBlocksForWindArrows(chart);

			};
			Meteogram.prototype.createChart = function () {
				var meteogram = this;
				this.chart = new Highcharts.Chart(this.getChartOptions(), function (chart) {
					meteogram.onChartLoad(chart);
				});
			};
			Meteogram.prototype.parseYrData = function () {

				var meteogram = this,
					xml = this.xml,
					pointStart;

				if (!xml) 
				    {
					$('#loading').html('<i class="fa fa-frown-o"></i> Failed loading data, please try again later');
					return;
					}
				pressureUnit = xml.properties.meta.units.air_pressure_at_sea_level;
				temperatureUnit = xml.properties.meta.units.air_temperature;
				precipitationUnit = xml.properties.meta.units.precipitation_amount;
				windSpeedUnit = xml.properties.meta.units.wind_speed;
				$.each(xml.properties.timeseries, function (i, element) {
                    //console.info("i->"+i);
					var from = element['time'],
					from  = from.replace(/-/g, '/').replace('T', ' ');
					from = from.replace(/-/g, '/').replace('Z', '');
					from0 = new Date(from);
					from  = Date.parse(from0);
					from  += <?php echo $stationOffsetSec ?> * 1000; // local time

                <?php if ($resolution == 'all'){ ?>
					if (element['data'].hasOwnProperty('next_1_hours'))
					  {to = from + 36e5; elab6h=0;}
					else if (element['data'].hasOwnProperty('next_6_hours'))
					  {to = from + 6*36e5; elab6h=1;}
					else
					  {return;}
					meteogram.resolution = 36e5;
                    //console.info("res=all i->",i," from->",from," to->",to);
                <?php } ?>
                <?php if ($resolution == '1h'){ ?>
					if (element['data'].hasOwnProperty('next_1_hours'))
					  {to = from + 36e5;}
					else
					  {return;}
			        meteogram.resolution = 36e5;
			        elab6h=0;
                    //console.info("res=1h i->",i," from->",from," to->",to);
                <?php } ?>
                <?php if ($resolution == '6h'){ ?>
					if ( (element['data'].hasOwnProperty('next_6_hours')) && (!element['data'].hasOwnProperty('next_1_hours')) )
					  {to = from + 6*36e5;}
					else if ( (element['data'].hasOwnProperty('next_6_hours')) && ((i%6)==0) )
					  {to = from + 6*36e5;}
					else
					  {return;}
			        meteogram.resolution = 6*36e5;
		            elab6h=1;
                    //console.info("res=6h i->",i," from->",from," to->",to);
                <?php } ?>

					//if (to > pointStart + 4 * 24 * 36e5) {return;}
					//if (i === 0) {meteogram.resolution = to - from;}

					meteogram.date.push(from);
					meteogram.temperatures.push({
						x: from,
						<?php 
							if($displayTempUnits=="C"){
								echo "y: parseInt(element.data.instant.details.air_temperature, 10),";
							}
							if($displayTempUnits=="F"){
								echo "y: Math.round((parseInt(element.data.instant.details.air_temperature, 10)*1.8)+32),";
							}
						?>					
						to: to,
						index: i
					});

					if ( element['data'].hasOwnProperty('next_6_hours') && (elab6h==1) )
					    {
					    meteogram.symbols.push(element.data.next_6_hours.summary.symbol_code.trim());
					    meteogram.precipitations.push({
						x: from,
						<?php
							if($displayRainUnits=="mm"){
								echo "y: parseFloat(element.data.next_6_hours.details.precipitation_amount)";
							}
							if($displayRainUnits=="in"){
								echo "y: Math.round(((element.data.next_6_hours.details.precipitation_amount)*0.0393701*100)/100)";
							}
						?>
						
					        });
					    }
					else if (element['data'].hasOwnProperty('next_1_hours'))
					    {
					    meteogram.symbols.push(element.data.next_1_hours.summary.symbol_code.trim());
                        //console.info("i->",i," symbol=",element.data.next_1_hours.summary.symbol_code);
					    meteogram.precipitations.push({
						x: from,
						<?php
							if($displayRainUnits=="mm"){
								echo "y: parseFloat(element.data.next_1_hours.details.precipitation_amount)";
							}
							if($displayRainUnits=="in"){
								echo "y: Math.round(((element.data.next_1_hours.details.precipitation_amount)*0.0393701*100)/100)";
							}
						?>
						
					        });
					    }
					meteogram.windDirections.push(parseFloat(element.data.instant.details.wind_from_direction));
					//meteogram.windDirectionNames.push(time.windDirection['@attributes'].name);
					<?php
						if($displayWindUnits=="ms")
						    {
							echo "meteogram.windSpeeds.push(element.data.instant.details.wind_speed);";
					    	echo "meteogram.windSpeed.push({x: from, y:element.data.instant.details.wind_speed});";
							}
						if($displayWindUnits=="kmh")
						    {
							echo "meteogram.windSpeeds.push(Math.round(parseFloat(element.data.instant.details.wind_speed)*3.6*10)/10);";
					    	echo "meteogram.windSpeed.push({x: from, y:Math.round(parseFloat(element.data.instant.details.wind_speed)*3.6*10)/10});";
							}
						if($displayWindUnits=="mph")
						    {
							echo "meteogram.windSpeeds.push(Math.round(parseFloat(element.data.instant.details.wind_speed)*2.23694*10)/10);";
					    	echo "meteogram.windSpeed.push({x: from, y:Math.round(parseFloat(element.data.instant.details.wind_speed)*2.23694*10)/10});";
							}
						if($displayWindUnits=="kt")
						    {
							echo "meteogram.windSpeeds.push(Math.round(parseFloat(element.data.instant.details.wind_speed)*1.943844*10)/10);";
					    	echo "meteogram.windSpeed.push({x: from, y:Math.round(parseFloat(element.data.instant.details.wind_speed)*1.943844*10)/10});";
							}
					?>				
					//meteogram.windSpeedNames.push(time.windSpeed['@attributes'].name);
					meteogram.pressures.push({
						x: from,
						<?php 
							if($displayPressUnits=="hpa"){
								echo "y: parseFloat(element.data.instant.details.air_pressure_at_sea_level)";
							}
							if($displayPressUnits=="inhg"){
								echo "y: Math.round((parseFloat(element.data.instant.details.air_pressure_at_sea_level)*0.0295299830714*100)/100)";
							}
							if($displayPressUnits=="mmhg"){
								echo "y: Math.round(parseFloat(element.data.instant.details.air_pressure_at_sea_level)*0.75006375541921)";
							}
						?>
					});

					if (i === 0) {
						pointStart = (from + to) / 2;
					}
				});
				if ((meteogram.temperatures.length % 2) == 1)
				    {
					meteogram.date.pop();
					meteogram.symbols.pop();
					meteogram.precipitations.pop();
					meteogram.windDirections.pop();
					meteogram.windSpeed.pop();
					meteogram.windSpeeds.pop();
					meteogram.windSpeedNames.pop();
					meteogram.temperatures.pop();
					meteogram.pressures.pop();
				    }
				this.smoothLine(this.temperatures);
				this.createChart();
			};
			$(document).ready(function(){
				$.getJSON(
					'homepage/blocks/meteogram/dataLoader.php?callback=?&id=0',
					function (xml) {
						window.meteogram = new Meteogram(xml, 'containerMeteogram');
					}
				);
				//loadMeteogramBlock();
			})
			$("#idLocationMeteogram").change(function(){
				loadMeteogramBlock();
			});
			function loadMeteogramBlock(){
				idLocation = $("#idLocationMeteogram").val();
				$.getJSON(
					'homepage/blocks/meteogram/dataLoader.php?callback=?&id='+idLocation,
					function (xml) {
						window.meteogram = new Meteogram(xml, 'containerMeteogram');
					}
				);
			}
		</script>
	
