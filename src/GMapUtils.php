<?php
declare(strict_types=1);

class GMapUtils
{
	use Nette\SmartObject;

	/**
	 * Get coordinates from address
	 * @param string $address
	 * @return array<string, string>
	 * @throws Exception
	 */
	public static function getCoordinatesFromAddress(string $address): array
	{
		$address = urlencode($address);
		$url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=' . $address;
		//$url = "http://maps.googleapis.com/maps/api/geocode/xml?address=prague";
		$response = file_get_contents($url);
		$json = json_decode($response, true);

		if (isset($json['error_message'])) {
			throw new Exception($json['error_message']);
		} elseif (!isset($json['results'][0])) {
			throw new Exception("Can't search address: " . $address);
		}

		$lat = $json['results'][0]['geometry']['location']['lat'];
		$lng = $json['results'][0]['geometry']['location']['lng'];

		return ['gps_lat' => $lat, 'gps_lon' => $lng];
	}


	/**
	 * @param float $lat
	 * @param float $lng
	 * @return string
	 * @throws Exception
	 */
	public static function getAddressFromCoordinates(float $lat, float $lng): string
	{
		$lat = urlencode($lat . '');
		$lng = urlencode($lng . '');
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&sensor=true";

		$response = file_get_contents($url);
		$json = json_decode($response, true);

		if (isset($json['error_message'])) {
			throw new Exception($json['error_message']);
		} elseif (!isset($json['results'][0]['formatted_address'])) {
			throw new Exception("Can't search address for GPS: " . $lat . ', ' . $lng);
		}

		return $json['results'][0]['formatted_address'];
	}
}
