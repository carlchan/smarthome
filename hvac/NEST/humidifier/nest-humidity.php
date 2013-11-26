#!/usr/bin/php
<?php

////////////////////////////
// Max automatic target humidity levels
$maxhumidity=40;

// Don't change this unless you're very very sure, or you may cause damage to your home!
$safelimit=60;

/////////////////////////////////////////
//// Nothing to change below  here //////
include 'nest-config.php';

function setHumidity($nest, $humidity) {
		$target=intval($humidity);
		if ($target > $GLOBALS['safelimit']) {
			echo "Error: Requested target exceeds safety limit.\n";
			return(1);
		} elseif ($target < 0 ) {
			$target=0;
		}
		echo "Setting humidity target to ".$target."%\n";
		$success=$nest->setHumidity($target);
		return($success);
}

if (count($argv)==1) {
	$infos = $nest->getDeviceInfo();
//	echo "Current humidity level: ".$infos->current_state->humidity."%\n";
	echo $infos->current_state->humidity."\n";
} elseif ($argv[1]=="auto") {
	$locationinfo = $nest->getUserLocations();
	$exttemp=round($locationinfo[0]->outside_temperature,0);
	if ($exttemp>=0) {
		$autotarget=$maxhumidity;
	} else {
                // Drop target humidity 5% for every 5degree C drop below 0
//              $autotarget=abs($maxhumidity-(5*abs($exttemp/5)));
                // Drop target humidity 8% for every 5degree C drop below 0
                $autotarget=abs($maxhumidity-(8*abs($exttemp/5)));
                if ($autotarget <= 10) {
                        $autotarget=10;
                }
	}
	$success=setHumidity($nest, $autotarget);
} elseif (is_numeric($argv[1])) {
	$success=setHumidity($nest, intval($argv[1]));
} else {
	echo "Invalid humidity value entered. Must be either 'auto' or a value between 0 and ".$safelimit.".\n";
}

exit(0);

?>
