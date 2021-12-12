<?php
declare(strict_types=1);
/**
 * Class AbstractNetteGMap
 * Base abstract map class for propertis in map
 */
class BaseNetteGMap extends Nette\Application\UI\Control
{
	private $child;

	/**
	 * Zoom of map (min: 0, max: 16)
	 * @var int Zoom of map
	 */
	private int $zoom;

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

	/** @var array $markers */
	private $markers;

	/** @var PolyLine $polyLine */
	private $polyLine;


	public function __construct($markers, $child)
	{
		$this->markers = $markers;
		$this->child = $child;

		//convert marker object to array
		for ($i = 0; $i < count($this->markers); $i++) {
			$this->markers[$i] = $this->markers[$i]->getArray();
		}
	}


	public function getMapParams()
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
	public function setPolyLine(PolyLine $polyLine)
	{
		$this->polyLine = $polyLine;
	}


	/**
	 * @param GpsPoint $centerMap
	 * Set hard center of map, if is null is set automatic from markers
	 */
	public function setCenterMap(GpsPoint $centerMap)
	{
		$this->centerMapGpsPoint = $centerMap;
	}


	/**
	 * SetWidht map
	 * @param string|int $width int(px), int[px], int[%]
	 */
	public function setWidth($width)
	{
		$this->sizeX = $this->codeDimension($width);
		return $this->child;
	}


	/**
	 * SetHeight map
	 * @param string|int $height int(px), int[px], int[%]
	 */
	public function setHeight($height)
	{
		$this->sizeY = $this->codeDimension($height);
		return $this->child;
	}


	/**
	 * @return int
	 */
	public function getZoom()
	{
		return $this->zoom;
	}


	/**
	 * SetZoom map
	 * @param int $zoom
	 * @return mixed
	 */
	public function setZoom(int $zoom)
	{
		$this->zoom = $zoom;
		return $this->child;
	}


	/**
	 * @return bool
	 */
	public function isScrollwheel()
	{
		return $this->scrollwheel;
	}


	/**
	 * @param bool $scrollwheel
	 */
	public function setScrollwheel($scrollwheel)
	{
		$this->scrollwheel = (bool) $scrollwheel;
		return $this->child;
	}


	/**
	 * @param string $dimension
	 * @return string
	 * @throws Exception
	 */
	private function codeDimension(string $dimension)
	{
		preg_match('/(\d+)(px|%)?/i', $dimension, $matches);

		$number = $matches[1];
		$scale = @$matches[2];

		if ($scale == '') {
			//integer, add px
			return $number . 'px';
		} elseif ($scale == 'px') {
			//is px
			return $number . '' . $scale;
		} elseif ($scale == '%') {
			//is %
			return $number . '' . $scale;
		} else {
			throw new \Exception('Dimensions must be in number, px or %');
		}
	}
}
