<?php

declare(strict_types=1);
use Nette\SmartObject;

class Marker
{
	use SmartObject;

	/** @var string */
	protected string $title;

	/** @var string */
	protected string $description;

	/** @var GpsPoint */
	protected GpsPoint $gpsPoint;

	/** @var string|null URL of icon */
	protected ?string $icon;


	/**
	 * @param string $title
	 * @param string $description
	 * @param mixed $latitude
	 * @param mixed $longitude
	 * @param string|null $icon
	 */
	public function __construct(
		string $title,
		string $description,
		$latitude,
		$longitude,
		?string $icon = null
	) {
		$this->setTitle($title);
		$this->setDescription($description);
		$this->gpsPoint = new GpsPoint($latitude, $longitude);
		$this->setIcon($icon);
	}


	public function getTitle(): string
	{
		return $this->title;
	}


	public function getDescription(): string
	{
		return $this->description;
	}


	public function getLatitude(): float
	{
		return $this->gpsPoint->getLatitude();
	}


	public function getLongitude(): float
	{
		return $this->gpsPoint->getLongitude();
	}


	public function getIcon(): string
	{
		return $this->icon;
	}


	public function setTitle(string $title): void
	{
		$this->title = $title;
	}


	public function setDescription(string $description): void
	{
		$this->description = $description;
	}


	public function setLatitude(float $latitude): void
	{
		$this->gpsPoint->setLatitude($latitude);
	}


	public function setLongitude(float $longitude): void
	{
		$this->gpsPoint->setLongitude($longitude);
	}


	public function setIcon(?string $icon): void
	{
		$this->icon = $icon;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function getArray(): array
	{
		$array = ['title' => $this->title, 'description' => $this->description, 'latitude' => $this->gpsPoint->getLatitude(), 'longitude' => $this->gpsPoint->getLongitude()];
		if ($this->icon != null) {
			$array['icon'] = $this->icon;
		}
		return $array;
	}
}
