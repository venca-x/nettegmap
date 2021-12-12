<?php
declare(strict_types=1);
use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

require __DIR__ . '/../src/GpsPoint.php';

class GpsPointTest extends Tester\TestCase
{
	public function setUp()
	{
	}


	public function testPoint()
	{
		$point = new GpsPoint(23.123, 32);
		Assert::same(23.123, $point->getLatitude());
		Assert::same(32.0, $point->getLongitude());

		$point->setLatitude(23.124);
		$point->setLongitude(33);
		Assert::same(23.124, $point->getLatitude());
		Assert::same(33.0, $point->getLongitude());


		$point = new GpsPoint(23.123, 32.0);
		Assert::same(23.123, $point->getLatitude());
		Assert::same(32.0, $point->getLongitude());

		$point->setLatitude('23,124');
		$point->setLongitude('33,0');
		Assert::same(23.124, $point->getLatitude());
		Assert::same(33.0, $point->getLongitude());
	}
}

$test = new GpsPointTest;
$test->run();
