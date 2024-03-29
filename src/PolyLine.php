<?php

declare(strict_types=1);
class PolyLine
{
	/** @var array<GpsPoint> */
	private array $coordinates;

	/** @var Stroke */
	private Stroke $stroke;


	/**
	 * @param array<GpsPoint> $coordinates
	 */
	public function __construct(array $coordinates)
	{
		$this->stroke = new Stroke();
		$this->coordinates = $coordinates;
	}


	/**
	 * @return array<string, mixed>
	 */
	public function getArray(): array
	{
		return ['stroke' => $this->stroke->getArray(), 'coordinates' => $this->coordinates];
	}
}
