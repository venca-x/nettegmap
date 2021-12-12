<?php
declare(strict_types=1);

/**
 * Class AbstractNetteGMap
 * Base abstract map class for propertis in map
 */
class BaseNetteGMap extends Nette\Application\UI\Control
{
	/** @var Nette\Application\UI\Control */
	private Nette\Application\UI\Control $child;

	/**
	 * Zoom of map (min: 0, max: 16)
	 * @var int|null Zoom of map
	 */
	private ?int $zoom;

	/** @var string X size map */
	private $sizeX = '100%';

	/** @var string Y size map */
	private $sizeY = '400px';

	/**
	 * @var GpsPoint
	 * Center of map
	 */
	private GpsPoint $centerMapGpsPoint;

	/** @var bool Zoom map with scroll wheal in mouse */
	private $scrollwheel = false;

	/** @var array<int, mixed> $markers */
	private array $markers;

	/** @var PolyLine $polyLine */
	private PolyLine $polyLine;


	/**
	 * @param array<Marker> $markers
	 * @param mixed $child
	 */
	public function __construct(array $markers, mixed $child)
	{
		$this->child = $child;

		//convert marker object to array
		foreach ($markers as $marker) {
			$this->markers[] = $marker->getArray();
		}
	}


	/**
	 * @return array<string, mixed>.
	 */
	public function getMapParams(): array
	{
		$array = [];

		$array['map'] = [
			'size' => [
				'x' => $this->sizeX,
				'y' => $this->sizeY,
			],
			'scrollwheel' => $this->scrollwheel,
		];

		if ($this->centerMapGpsPoint != null) {
			$array['map']['center'] = [
				'lat' => $this->centerMapGpsPoint->getLongitude(),
				'lng' => $this->centerMapGpsPoint->getLatitude(),
			];
		}

		if ($this->zoom != null) {
			$array['map']['zoom'] = $this->zoom;
		}


		if (count($this->markers) > 0) {
			$array['markers'] = $this->markers;
		}

		if ($this->polyLine != null) {
			$array['polyline'] = $this->polyLine->getArray();
		}

		return $array;
	}


	/**
	 * Add Poly line to map
	 * @param PolyLine $polyLine
	 */
	public function setPolyLine(PolyLine $polyLine): void
	{
		$this->polyLine = $polyLine;
	}


	/**
	 * @param GpsPoint $centerMap
	 * Set hard center of map, if is null is set automatic from markers
	 */
	public function setCenterMap(GpsPoint $centerMap): void
	{
		$this->centerMapGpsPoint = $centerMap;
	}


	/**
	 * SetWidht map
	 * @param string $width int(px), int[px], int[%]
	 * @return \Nette\Application\UI\Control
	 * @throws Exception
	 */
	public function setWidth(string $width): Nette\Application\UI\Control
	{
		$this->sizeX = $this->codeDimension($width);
		return $this->child;
	}


	/**
	 * SetHeight map
	 * @param string $height int(px), int[px], int[%]
	 * @return \Nette\Application\UI\Control
	 * @throws Exception
	 */
	public function setHeight(string $height): Nette\Application\UI\Control
	{
		$this->sizeY = $this->codeDimension($height);
		return $this->child;
	}


	public function getZoom(): ?int
	{
		return $this->zoom;
	}


	/**
	 * SetZoom map
	 * @param int $zoom
	 * @return \Nette\Application\UI\Control
	 */
	public function setZoom(int $zoom): Nette\Application\UI\Control
	{
		$this->zoom = $zoom;
		return $this->child;
	}


	public function isScrollWheel(): bool
	{
		return $this->scrollwheel;
	}


	public function setScrollWheel(bool $scrollWheel): Nette\Application\UI\Control
	{
		$this->scrollwheel = $scrollWheel;
		return $this->child;
	}


	/**
	 * @param string $dimension
	 * @return string
	 * @throws Exception
	 */
	private function codeDimension(string $dimension): string
	{
		preg_match('/(\d+)(px|%)?/i', $dimension, $matches);

		$number = $matches[1];
		$unit = @$matches[2];

		if ($unit == '') {
			//integer, add px
			return $number . 'px';
		} elseif ($unit == 'px') {
			//is px
			return $number . '' . $unit;
		} elseif ($unit == '%') {
			//is %
			return $number . '' . $unit;
		} else {
			throw new \Exception('Dimensions must be in number, px or %');
		}
	}
}
