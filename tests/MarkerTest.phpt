<?php
declare(strict_types=1);

use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

class MarkerTest extends Tester\TestCase
{
	public function setUp()
	{
	}


	public function testMarker()
	{
		Assert::type(Marker::class, new Marker('title1', 'desc1', '40.6898059', '-74.0450227'));
		Assert::type(Marker::class, new Marker('title2', 'desc2', 40.6898059, -74.0450227));
	}
}


$test = new MarkerTest;
$test->run();
