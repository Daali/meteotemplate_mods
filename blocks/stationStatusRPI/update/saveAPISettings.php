<?php
	// check acces authorization
	session_start();
	if($_SESSION['user']!="admin"){
		echo "Unauthorized access.";
		die();
	}
	
	include("../config.php");
	
	//error_reporting(E_ALL);
	
	// load user settings
	foreach($_GET as $key=>$value){
		$parameters[trim($key)] = $value;
		if($value==1){
			$extraCols[] = trim($key);
		}
	}
	
	if(file_exists("apiSettings.txt")){
		unlink("apiSettings.txt");
	}
	file_put_contents("apiSettings.txt",json_encode($parameters));

	// check if table already exists
	if(mysqli_num_rows(mysqli_query($con,"SHOW TABLES LIKE 'alldataExtra'")) > 0){
		// table already exists
        // echo "Table already exists.<br>";
	}
    else{
        $query = 
        "	
            CREATE  TABLE  alldataExtra (  
                DateTime datetime NOT  NULL  PRIMARY  KEY
            ) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8 COLLATE  = utf8_unicode_ci;
		";
        //echo $query;
        mysqli_query($con, $query);
        //echo "Creating extra data table...<br>";

        // check if table was created successfully
        if(
            mysqli_num_rows(
                mysqli_query($con,
                    "
                        SHOW TABLES LIKE 'alldataExtra'
                    "
                )
            ) > 0
            or die ("Table was not created, please check your MySQL setup.")
        ){
            //echo "Table created!<br>";
        }
    }

    // echo "<br>Checking columns...<br>";

    // add columns
    for($i=0;$i<count($extraCols);$i++){
        $thisCol = $extraCols[$i];
        //echo "<br>Checking column: ".$thisCol."<br>";
        $query = "SHOW COLUMNS FROM `alldataExtra` LIKE '".$thisCol."'";
        $result = mysqli_query($con, $query);
        $exists = (mysqli_num_rows($result)) ? true : false;
        if(!$exists){
            //echo "This column does not exist, creating it.<br>";
            if($thisCol=="UV"){
                // UV
                $query = "ALTER TABLE  `alldataExtra` ADD  `UV` DECIMAL(3,1) NULL";
            }
            else if($thisCol=="TIN" || similar($thisCol,"T") || similar($thisCol,"TS")){
                // extra temperature / indoor temperature / soil temperature [deg C]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(3,1) NULL";
            }
            else if($thisCol=="HIN" || similar($thisCol,"H")){
                // extra humidity / indoor humidity [%]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(4,1) NULL";
            }
            else if(similar($thisCol,"LT")){
                // leaf temperature
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(4,1) NULL";
            }
            else if(similar($thisCol,"LW")){
                // leaf wetness
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(4,1) NULL";
            }
            else if($thisCol=="SD"){
                // snow depth [mm]
                $query = "ALTER TABLE  `alldataExtra` ADD  `SD` DECIMAL(6,1) NULL";
            }
            else if($thisCol=="SN"){
                // snowfall [mm]
                $query = "ALTER TABLE  `alldataExtra` ADD  `SN` DECIMAL(5,1) NULL";
            }
            else if(similar($thisCol,"SM")){
                // soil moisture [%]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(4,1) NULL";
            }
            else if($thisCol=="L"){
                // lightning count
                $query = "ALTER TABLE  `alldataExtra` ADD  `L` INT(4) NULL";
            }
            else if($thisCol=="LD"){
                // lightning count
                $query = "ALTER TABLE  `alldataExtra` ADD  `LD` INT(2) NULL";
            }	
            else if($thisCol=="LT"){
                // lightning count
                $query = "ALTER TABLE  `alldataExtra` ADD  `LT` INT(10) NULL";
            }			
            else if($thisCol=="NL"){
                // noise level [dB]
                $query = "ALTER TABLE  `alldataExtra` ADD  `NL` DECIMAL(4,1) NULL";
            }
            else if($thisCol=="SS"){
                // sunshine [h]
                $query = "ALTER TABLE  `alldataExtra` ADD  `SS` DECIMAL(4,1) NULL";
            }
            else if(similar($thisCol,"CO2_")){
                // CO2 [ppm]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(5,1) NULL";
            }
            else if(similar($thisCol,"NO2_")){
                // NO2 [ppm]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(5,1) NULL";
            }
            else if(similar($thisCol,"CO_")){
                // CO [ppm]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(5,1) NULL";
            }
            else if(similar($thisCol,"SO2_")){
                // SO2 [ppb]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(5,1) NULL";
            }
            else if(similar($thisCol,"O3_")){
                // O3 [ppb]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(5,1) NULL";
            }
            else if(similar($thisCol,"PP")){
                // particulate pollution [ug/m3]
                $query = "ALTER TABLE  `alldataExtra` ADD  `".$thisCol."` DECIMAL(5,1) NULL";
            }
            mysqli_query($con, $query);
        }
        else{
            //echo "This column already exists, skipping.<br>";
        }
    }

	// check file exists
	
	if(!file_exists("apiSettings.txt")){
		echo "<script>alert('API settings file could not be created! Check that permissions for the update folder are set correctly to write files in there!');close();</script>";
	}
	else{
		print "<script>alert('API settings file created/updated and alldataExtra table created/updated.');close();</script>";
	}

function similar($field, $newField)

{
$l = strlen($newField);
if (strlen($field) == $l+1)
   {
   if ( (substr($field,0,$l) == $newField) && is_numeric(substr($field,$l,1)) )
        return true;
   else 
   		return false;
   }
return false;
}
?>