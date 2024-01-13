<?php

declare(strict_types=1);

class Stroke
{
	private string $color = '#FF0000';
	private float $opacity = 1.0;
	private int $weight = 2;


	/**
	 * @return array<string, mixed>
	 */
	public function getArray(): array
	{
		return [
			'color' => $this->color,
			'opacity' => $this->opacity,
			'weight' => $this->weight,
		];
	}


	public function getColor(): string
	{
		return $this->color;
	}


	public function setColor(string $color): void
	{
		$this->color = $color;
	}


	public function getOpacity(): float
	{
		return $this->opacity;
	}


	public function setOpacity(float $opacity): void
	{
		$this->opacity = $opacity;
	}


	public function getWeight(): int
	{
		return $this->weight;
	}


	public function setWeight(int $weight): void
	{
		$this->weight = $weight;
	}
}
