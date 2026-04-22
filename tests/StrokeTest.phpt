<?php

declare(strict_types=1);
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';


class StrokeTest extends TestCase
{
	public function testDefaultsAndMutators(): void
	{
		$s = new Stroke;
		Assert::same('#FF0000', $s->getColor());
		Assert::same(1.0, $s->getOpacity());
		Assert::same(2, $s->getWeight());
		$a = $s->getArray();
		Assert::same('#FF0000', $a['color']);
		Assert::same(1.0, $a['opacity']);
		Assert::same(2, $a['weight']);

		$s->setColor('#00FF00');
		$s->setOpacity(0.5);
		$s->setWeight(4);
		Assert::same('#00FF00', $s->getColor());
		Assert::same(0.5, $s->getOpacity());
		Assert::same(4, $s->getWeight());
	}
}


$test = new StrokeTest;
$test->run();
