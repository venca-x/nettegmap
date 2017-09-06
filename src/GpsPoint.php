<?php
declare(strict_types=1);
/**
 * Class Point
 * GPS point
 */
class GpsPoint extends Nette\Object
{

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
	 * @return Double
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}


	/**
	 * @param $latitude
	 */
	public function setLatitude($latitude)
	{
		$this->latitude = (float) $this->setDimension($latitude);
	}


	/**
	 * @return Double Double
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}


	/**
	 * @param $longitude
	 */
	public function setLongitude($longitude)
	{
		$this->longitude = (float) $this->setDimension($longitude);
	}
}
