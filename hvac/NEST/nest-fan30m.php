#!/usr/bin/php
<?php
include 'nest-config.php';

$success = $nest->setFanModeOnWithTimer(FAN_TIMER_30M);

exit($success);

?>
