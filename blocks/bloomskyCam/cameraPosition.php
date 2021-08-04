<?php

    include("../../../config.php");
	include($baseURL."header.php");

    $lat = $_GET['lat'];
    $lon = $_GET['lon'];

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Webcam</title>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo $googleMapsAPIKey?>"></script>
		<style>
			#map {
				width: 100%;
				height: <?php echo $_GET['height']?>px;
			}
        </style>
    </head>
    <body onload="initialize()">
        <div id="map"></div>
    </body>
    <script>
        function initialize() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 6,
                center: new google.maps.LatLng(<?php echo $lat.",".$lon?>),
                mapTypeId: google.maps.MapTypeId.HYBRID
            });
            var myLatLng = {lat: <?php echo $lat?>, lng: <?php echo $lon?>};	
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: ''
            });	
        }
    </script>
</html>