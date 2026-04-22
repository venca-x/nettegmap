<?php

declare(strict_types=1);
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';


class PolyLineTest extends TestCase
{
	public function testGetArray(): void
	{
		$pts = [new GpsPoint(1.0, 2.0), new GpsPoint(3.0, 4.0)];
		$pl = new PolyLine($pts);
		$a = $pl->getArray();
		Assert::type('array', $a['stroke']);
		Assert::count(2, $a['coordinates']);
		Assert::same(1.0, $a['coordinates'][0]->getLatitude());
	}
}


$test = new PolyLineTest;
$test->run();
