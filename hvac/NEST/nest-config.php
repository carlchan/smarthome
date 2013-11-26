<?php

// Your NEST credentials
define('USERNAME', 'NESTUSERNAME');
define('PASSWORD', 'NESTPASSWORD');
date_default_timezone_set('America/NewYork');

/////////////////////////////////////////
//// Nothing to change below  here //////

require_once('nest.class.php');
$nest = new Nest();
$infos = $nest->getDeviceInfo();
$infoLoc = $nest->getUserLocations();

?>

