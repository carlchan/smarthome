#!/usr/bin/php
<?php
include('nest-config.php');

if (count($argv)==1) {
//	$infos = $nest->getDeviceInfo();
	$infoLoc = $nest->getUserLocations();
	if ($infoLoc[0]->away == "1") {
		echo "Current State: Away\n";
	} else {
		echo "Current State: Home\n";
	}
} else {
	switch (strtolower($argv[1])) {
		case ("away"):
		case ("on"):
			$nest->setAway(AWAY_MODE_ON);
			break;
		case ("home"):
		case ("off"):
			$nest->setAway(AWAY_MODE_OFF);
			break;
		default:
			echo "Unknown mode\n";
			exit(1);
	}
}

exit(0);

?>
