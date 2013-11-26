#!/usr/bin/php
<?php
include 'nest-config.php';

$success = $nest->setFanModeOnWithTimer(FAN_TIMER_2H);

exit($success);

?>
