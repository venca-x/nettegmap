<?php
declare(strict_types=1);
class GMapUtils extends Nette\Object
{


	/**
	 * Get coordinates from address
	 * @param $address
	 * @return array
	 */
	public static function getCoordinatesFromAddress($address)
	{
		$address = urlencode($address);
		$url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=' . $address;
		//$url = "http://maps.googleapis.com/maps/api/geocode/xml?address=prague";
		$response = file_get_contents($url);
		$json = json_decode($response, true);

		if (!isset($json['results'][0])) {
			throw new Exception("Can't search address: " . $address);
		}

		$lat = $json['results'][0]['geometry']['location']['lat'];
		$lng = $json['results'][0]['geometry']['location']['lng'];

		return ['gps_lat' => $lat, 'gps_lon' => $lng];
	}


	public static function getAddressFromCoordinates($lat, $lng)
	{
		$lat = urlencode($lat."");
		$lng = urlencode($lng."");
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&sensor=true";

		$response = file_get_contents($url);
		$json = json_decode($response, true);

		if (!isset($json['results'][0]['formatted_address'])) {
			throw new Exception("Can't search address for GPS: " . $lat . ', ' . $lng);
		}

		return $json['results'][0]['formatted_address'];
	}
}
