#!/usr/bin/php
<?php

////////////////////////////

// Don't change this unless you're very very sure, or you may cause damage to your home!
$cooltarget=24.5;
$heattarget=19.0;

/////////////////////////////////////////
//// Nothing to change below  here //////
include 'nest-config.php';

$infos = $nest->getDeviceInfo();
$infoLoc = $nest->getUserLocations();

$currentmode=explode(',', $infos->current_state->mode);
$mode=$currentmode[0];

if ($infoLoc[0]->away!="1") {
	if ($argv[1]=="heat" && $mode != "heat") {
		$success=$nest->setTargetTemperatureMode(TARGET_TEMP_MODE_HEAT, $heattarget);
		sleep(10);
		$nest->setTargetTemperature($heattarget);
	} elseif ($argv[1]=="cool" && $mode != "cool") {
		$success=$nest->setTargetTemperatureMode(TARGET_TEMP_MODE_COOL, $cooltarget);
		sleep(10);
		$nest->setTargetTemperature($cooltarget);
	} elseif ($argv[1]=="range" && $mode != "range") {
	        $success=$nest->setTargetTemperatureMode(TARGET_TEMP_MODE_RANGE, array(19.0,25.5));
		sleep(10);
		$nest->setTargetTemperatures(19.0, 25.5);
	}
}

exit($success);

?>
