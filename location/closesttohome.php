#!/usr/bin/php
<?PHP
///////////////////////
// Locates iPhone(s) and returns estimated distance away from home of the closest phone in minutes of driving time
//////////////////////
$auth = array();
$auth['USERNAME'] = array(
	"password" => "PASSWORD",
	"deviceName" => "DEVICENAME",
	"id" => "DEVICEID"
);
$home = array(
	'latitude' => 'LATITUDE',
	'longitude' => 'LONGITUDE'
);

/////////////////////

// from http://www.paul-norman.co.uk/2009/07/using-google-to-calculate-driving-distance-time-in-php/
function get_driving_information($start, $finish, $raw = false)
{
    if(strcmp($start, $finish) == 0)
    {
        $time = 0;
        if($raw)
        {
            $time .= ' seconds';
        }

        return array('distance' => 0, 'time' => $time);
    }

    $start  = urlencode($start);
    $finish = urlencode($finish);

    $distance   = 'unknown';
    $time       = 'unknown';

    $url = 'http://maps.googleapis.com/maps/api/directions/xml?origin='.$start.'&destination='.$finish.'&sensor=false';
    if($data = file_get_contents($url))
    {
        $xml = new SimpleXMLElement($data);

        if(isset($xml->route->leg->duration->value) AND (int)$xml->route->leg->duration->value > 0)
        {
            if($raw)
            {
                $distance = (string)$xml->route->leg->distance->text;
                $time     = (string)$xml->route->leg->duration->text;
            }
            else
            {
                $distance = (int)$xml->route->leg->distance->value / 1000;
                $time     = (int)$xml->route->leg->duration->value;
            }
        }
        else
        {
            throw new Exception('Could not find that route');
        }

        return array('distance' => $distance, 'time' => $time);
    }
    else
    {
        throw new Exception('Could not resolve URL');
    }
}

function locatePhone($user,$pass,$search) {
	global $home;

	//Initialize Sosumi library and log in to Find My iPhone
	try {
		$ssm = new Sosumi($user, $pass);
	} catch (Exception $e) {
		echo "Unable to log in to iCloud\n";
		exit(1);
	}

	//Search list of devices for specific device
	foreach ($ssm->devices as $id => $device) {
		if ($device->id == $search) {
			$phone=$ssm->locate($id);
		}
	}

	///////////////
	if (!isset($phone)) {
		print("Phone not found!\n");
		exit(1);
	}

	//calculate distance
	// from http://www.movable-type.co.uk/scripts/latlong.html
	$R=6371;
	$dLat=deg2rad($home['latitude']-$phone['latitude']);
	$dLong=deg2rad($home['longitude']-$phone['longitude']);
	$radHomeLat=deg2rad($home['latitude']);
	$radPhoneLat=deg2rad($phone['latitude']);
	$a=(pow(sin($dLat/2),2) + pow(sin($dLong/2),2) * cos($radHomeLat) * cos($radPhoneLat));
	$c=(2 * atan2(sqrt($a),sqrt(1-$a)));
	$d=round(($R * $c),2);

	$info=get_driving_information(($phone['latitude'].",".$phone['longitude']),($home['latitude'].",".$home['longitude']));
	//	echo($d."KM away\n");
	//	echo(($info['distance'])." KM away driving distance\n");
	unset($phone);
	return(round(($info['time']/60),0)."\n");
}

///////////////////////////////////////////
require 'class.sosumi.php';

$closest=99999;

foreach ($auth as $username=>$user) {
//	print($username." ".$user['password']." ".$user['deviceName']."\n");
	$timeaway=(locatePhone($username, $user['password'], $user['id']));
	if ($timeaway < $closest) {
		$closest=$timeaway;
	}
}

print($closest);

exit(0);

?>
