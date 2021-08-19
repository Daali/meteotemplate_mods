1. set up periodic http request in meteobridge
   https://<yoururl>/api?PASS=<your_api_password>&U=[epoch]&RPITEMP=[t9temp-act]&RPILOAD=[data10num-act]&RPIPROC=[data15num-act]&RPIDAGE=[data16num-act]
2. copy and replace api.php to the root of your meteotemplate
3. copy all /update files to your /update folder.  This will overwrite /update/apiSetup.php and /update/saveAPISettings.php
4. run control panel -> extra sensors to add new data to database.  Make sure to select "Save in database" and save the page
		Meteobridge RPI Temp
		Meteobridge RPI Load
		Meteobridge RPI Processes
		Meteobridge RPI Data Age
5. install block
