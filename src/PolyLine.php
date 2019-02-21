<?php
declare(strict_types=1);
class PolyLine
{

	/** @var array GpsPoint */
	private $coordinates;

	/** @var Stroke */
	private $stroke;


	/**
	 * PolyLine constructor.
	 * @param $coordinates array GpsPoint
	 */
	public function __construct($coordinates)
	{
		$this->stroke = new Stroke();
		$this->coordinates = $coordinates;
	}


	public function getArray()
	{
		return ['stroke' => $this->stroke->getArray(), 'coordinates' => $this->coordinates];
	}
}
