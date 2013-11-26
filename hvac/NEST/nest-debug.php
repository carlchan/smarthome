#!/usr/bin/php
<?php
include 'nest-config.php';

$infos = $nest->getDeviceInfo();
$infoLoc = $nest->getUserLocations();

$next_event = $nest->getNextScheduledEvent();

print_r($infos);
print_r($infoLoc);

print_r($next_event);

?>
