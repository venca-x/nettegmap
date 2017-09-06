<?php
declare(strict_types=1);

class Marker
{
	use Nette\SmartObject;

	/** @var string */
	protected $title;

	/** @var string */
	protected $description;

	/** @var GpsPoint */
	protected $gpsPoint;

	/** @var url */
	protected $icon;


	public function __construct($title, $description, $latitude, $longitude, $icon = null)
	{
		$this->setTitle($title);
		$this->setDescription($description);
		$this->gpsPoint = new GpsPoint($latitude, $longitude);
		$this->setIcon($icon);
	}


	public function getTitle()
	{
		return $this->title;
	}


	public function getDescription()
	{
		return $this->description;
	}


	public function getLatitude()
	{
		return $this->latitude;
	}


	public function getLongitude()
	{
		return $this->longitude;
	}


	public function getIcon()
	{
		return $this->icon;
	}


	public function setTitle($title)
	{
		$this->title = $title;
	}


	public function setDescription($description)
	{
		$this->description = $description;
	}


	public function setLatitude($latitude)
	{
		$this->gpsPoint->setLatitude($latitude);
	}


	public function setLongitude($longitude)
	{
		$this->gpsPoint->setLongitude($longitude);
	}


	public function setIcon($icon)
	{
		$this->icon = $icon;
	}


	public function getArray()
	{
		$array = ['title' => $this->title, 'description' => $this->description, 'latitude' => $this->gpsPoint->getLatitude(), 'longitude' => $this->gpsPoint->getLongitude()];
		if ($this->icon != null) {
			$array['icon'] = $this->icon;
		}
		return $array;
	}
}
