#!/usr/bin/php
<?php
include 'nest-config.php';

$infoLoc = $nest->getUserLocations();

if (count($argv) > 1) {
	if ($argv[1] == "on") {
		$nest->setFanModeMinutesPerHour(FAN_MODE_MINUTES_PER_HOUR_30);
	        $success = $nest->setFanMode(FAN_MODE_EVERY_DAY_ON);
	} elseif ($argv[1] == "off") {
	        $success = $nest->setFanMode(FAN_MODE_AUTO);
	}
} else {
       if ($infoLoc[0]->away == "1") {
                $success = $nest->setFanMode(FAN_MODE_AUTO);
        } else {
		$success = $nest->setFanModeMinutesPerHour(FAN_MODE_MINUTES_PER_HOUR_30);
                $success = $nest->setFanMode(FAN_MODE_EVERY_DAY_ON);
        }
}

exit($success);

?>
