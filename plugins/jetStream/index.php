<?php
	
	############################################################################
	# 	
	#	Meteotemplate
	# 	http://www.meteotemplate.com
	# 	Free website template for weather enthusiasts
	# 	Author: Jachym
	#           Brno, Czech Republic
	# 	First release: 2015
	#
	############################################################################
	#
	#	Jet Stream
	#
	# 	A script which shows current jet streams.
	#
	############################################################################
	#	Version and change log
	#
	# 	v1.0 	2017-02-24	Initial release
	#   v1.1    2021-08-03  Daali hack website closed, changed image to only NA
	#
	############################################################################
	
	include("../../config.php");
	include($baseURL."css/design.php");
	include($baseURL."header.php");

?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $pageName?></title>
		<?php metaHeader()?>
		<style>
			#jetStreamDiv{
				width: 96%;
				padding: 1%;
				margin: 0 auto;
				background: #<?php echo $color_schemes[$design2]['900']?>;
				border-radius: 15px;
			}
			#jetDescDiv{
				width: 96%;
				padding: 1%;
				margin: 0 auto;
				background: #<?php echo $color_schemes[$design2]['700']?>;
				border-radius: 15px;
				text-align:justify;
			}
			.jetImage{
				border-radius: 10px;
				border: 1px solid #<?php echo $color_schemes[$design2]['400']?>;
				cursor: pointer;
				opacity: 0.8;
				width: 98%;
				margin: 0 auto;
			}
			.jetImage:hover{
				opacity:1;
			}
			#main p{
				text-indent: 15px;
			}
		</style>
	</head>
	<body>
		<div id="main_top">
			<?php bodyHeader()?>
			<?php include($baseURL."menu.php");?>
		</div>
		<div id="main" style="text-align:center">
			<h1><?php echo lang('jet stream','c')?></h1>
			<div id="jetDescDiv">
				<p>
					Jet streams are fast flowing, narrow, meandering air currents found in the atmosphere of some planets, including Earth. On Earth, the main jet streams are located near the altitude of the tropopause and are westerly winds (flowing west to east). Their paths typically have a meandering shape. Jet streams may start, stop, split into two or more parts, combine into one stream, or flow in various directions including opposite to the direction of the remainder of the jet.
				</p>
				<p>
					The strongest jet streams are the polar jets, at 9–12 km (30,000–39,000 ft) above sea level, and the higher altitude and somewhat weaker subtropical jets at 10–16 km (33,000–52,000 ft). The Northern Hemisphere and the Southern Hemisphere each have a polar jet and a subtropical jet. The northern hemisphere polar jet flows over the middle to northern latitudes of North America, Europe, and Asia and their intervening oceans, while the southern hemisphere polar jet mostly circles Antarctica all year round.
				</p>
				<p>
					Jet streams are the product of two factors:
					<ul>
						<li>
							the atmospheric heating by solar radiation that produces the large scale Polar, Ferrel, and Hadley circulation cells
						</li>
						<li>
							the action of the Coriolis force (caused by the planet's rotation on its axis) acting on those moving masses
						</li>
					</ul>
					The Polar jet stream forms near the interface of the Polar and Ferrel circulation cells; while the subtropical jet forms near the boundary of the Ferrel and Hadley circulation cells.
				</p>
				<p>
					Other jet streams also exist. During the Northern Hemisphere summer, easterly jets can form in tropical regions, typically where dry air encounters more humid air at high altitudes. Low-level jets also are typical of various regions such as the central United States. Meteorologists use the location of some of the jet streams as an aid in weather forecasting. The main commercial relevance of the jet streams is in air travel, as flight time can be dramatically affected by either flying with the flow or against. Clear-air turbulence, a potential hazard to aircraft passenger safety, is often found in a jet stream's vicinity, but it does not create a substantial alteration on flight times.
				</p>
			</div>
			<br>
			<div id="jetStreamDiv">
				<table style="width:98%;margin:0 auto" cellspacing="4" cellpadding="4">
					<tr>
						<td style="width:98%">
							<h2><?php echo lang('north america','c')?></h2>
							<img src="	https://s.w-x.co/staticmaps/wu/wu/jetstream1200_cur/conus/animate.png" class="jetImage">
						</td>
				</table>
			</div>
			
		</div>
		<?php include($baseURL."footer.php");?>
		<script>
			dialogHeight = screen.height*0.8;
			dialogWidth = screen.width*0.9;
			$(".jetImage").click(function(){
				url = $(this).attr("src");
				$("#jetStreamWindow").html("<div style='width:98%;margin:0 auto;text-align:center'><img src='"+url+"' style='width:100%'></div>");
				$("#jetStreamWindow").dialog('open');
			})
			
			$("#jetStreamWindow").dialog({
				modal: true,
				autoOpen: false,
				height: dialogHeight,
				width: dialogWidth
			});
		</script>
	</body>
</html>
	