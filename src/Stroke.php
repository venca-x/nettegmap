<?php
declare(strict_types=1);
class Stroke
{
	private $color = '#FF0000';
	private $opacity = 1.0;
	private $weight = 2;


	/**
	 * Stroke constructor.
	 */
	public function __construct()
	{
	}


	public function getArray()
	{
		return [
			'color' => $this->color,
			'opacity' => $this->opacity,
			'weight' => $this->weight,
			];
	}


	/**
	 * @return string
	 */
	public function getColor()
	{
		return $this->color;
	}


	/**
	 * @param string $color
	 */
	public function setColor($color)
	{
		$this->color = $color;
	}


	/**
	 * @return float
	 */
	public function getOpacity()
	{
		return $this->opacity;
	}


	/**
	 * @param float $opacity
	 */
	public function setOpacity($opacity)
	{
		$this->opacity = $opacity;
	}


	/**
	 * @return int
	 */
	public function getWeight()
	{
		return $this->weight;
	}


	/**
	 * @param int $weight
	 */
	public function setWeight($weight)
	{
		$this->weight = $weight;
	}
}
