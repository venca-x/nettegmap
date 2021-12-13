<?php
declare(strict_types=1);

/**
 * Class Point
 * GPS point
 */
class GpsPoint
{
	use Nette\SmartObject;

	/** @var float */
	public $latitude;

	/** @var float */
	public $longitude;


	/**
	 * @param mixed $latitude
	 * @param mixed $longitude
	 */
	public function __construct($latitude, $longitude)
	{
		$this->setLatitude($latitude);
		$this->setLongitude($longitude);
	}


	private function setDimension(string $dimension): float
	{
		return (float) str_replace(',', '.', $dimension);
	}


	public function getLatitude(): float
	{
		return $this->latitude;
	}


	/**
	 * @param mixed $latitude
	 */
	public function setLatitude($latitude): void
	{
		if (is_float($latitude)) {
			$this->latitude = $latitude;
		} else {
			$this->latitude = $this->setDimension($latitude . '');
		}
	}


	public function getLongitude(): float
	{
		return $this->longitude;
	}


	/**
	 * @param mixed $longitude
	 */
	public function setLongitude($longitude): void
	{
		if (is_float($longitude)) {
			$this->longitude = $longitude;
		} else {
			$this->longitude = $this->setDimension($longitude . '');
		}
	}
}
