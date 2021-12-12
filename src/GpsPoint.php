<?php
declare(strict_types=1);

/**
 * Class Point
 * GPS point
 */
class GpsPoint
{
	use Nette\SmartObject;

	/** @var double */
	public $latitude;

	/** @var double */
	public $longitude;


	public function __construct($latitude, $longitude)
	{
		$this->setLatitude($latitude);
		$this->setLongitude($longitude);
	}


	private function setDimension($dimension)
	{
		return str_replace(',', '.', $dimension);
	}


	/**
	 * @return double
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}


	public function setLatitude($latitude)
	{
		$this->latitude = (float) $this->setDimension($latitude);
	}


	/**
	 * @return double Double
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}


	public function setLongitude($longitude)
	{
		$this->longitude = (float) $this->setDimension($longitude);
	}
}
