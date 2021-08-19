# meteotemplate_mods
modifications to meteotemplate for my own use.  Not polished or publishable material, very hacky.


# added mod to windy block
* to add settings.php to enable control of default block loaded map overlay
* ![windy](https://user-images.githubusercontent.com/451339/127758514-dd61ced1-eaf3-4c98-a2b7-86c3da321893.png)

# added NWSRadarIframe block
* sets NWS Radar in an Iframe block
* added settings.php and instructions
* ![NWSRadarIframe](https://user-images.githubusercontent.com/451339/127758508-c120bee7-abce-4db8-afac-6d44608cc584.png)

# added davidefa's ecowittModules hack aka ecowittModules_daali_hack
* while only using FOSHKplugin to feed meteotemplate api, I am not getting the battery detail
* so I removed the graphics for battery level and signal
* Changed WS68BAT to WBAT since that is what the FOSHKplugin MT api puts in meteobridgeLive.txt
* Changed RBAT to WBAT since the WH40 rain sensor does not send battery information
* Changed PP1BAT to PM1BAT since that is what the FOSHKplugin MT api puts in meteobridgeLive.txt
 ![ecowittModules_daali_hack](https://user-images.githubusercontent.com/451339/127936364-083c44c4-f8a2-4588-8583-a9d18b52e1d7.png)
 
 # added hack to webcam block (bloomskyCam block) for simplifying use of bloomSky latest image
 * ![bloomskyCam](https://user-images.githubusercontent.com/451339/128084054-5a251855-2f2c-46a2-b9c7-8e1a05eea817.png)

# added fixed jetStream plugin.  
* original website owner passed, college shut down operations.  Fixed for North America with WU image
*  ![jetStream](https://user-images.githubusercontent.com/451339/128101333-d3c6fa51-bf29-4898-b864-73ee9bbff3d4.png)

# added radarBE block
* fixed expired ajax loop to iframe rainviewers radar
*  ![radarBE](https://user-images.githubusercontent.com/451339/128531362-9a7ab846-14f0-46ff-84df-489e19117dd5.png)

# added radarRainviewer block
* new block to use radarBE's rainviewer iframe but to set location of station as default load

# added stationStatusRPI block
* new block to show meteobridge RPI status in the stationStatus. 
* added hack of davidefa's api.php, /update/apiSetup.php and /update/saveAPISettings.php 
