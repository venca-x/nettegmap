<?php

declare(strict_types=1);

use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';

class MarkerTest extends TestCase
{
	public function setUp()
	{
	}


	public function testMarkerWithIconInArray(): void
	{
		$m = new Marker('t1', 'd1', '40.0', '-74.0', 'https://x/icon.png');
		Assert::same('t1', $m->getTitle());
		Assert::same('d1', $m->getDescription());
		Assert::same(40.0, $m->getLatitude());
		Assert::same(-74.0, $m->getLongitude());
		Assert::same('https://x/icon.png', $m->getIcon());

		$a = $m->getArray();
		Assert::same('https://x/icon.png', $a['icon']);

		$m->setTitle('t2');
		$m->setDescription('d2');
		$m->setLatitude(41.0);
		$m->setLongitude(-73.0);
		$m->setIcon(null);
		Assert::same('t2', $m->getTitle());
	}


	public function testMarkerWithoutIconKeyInArray(): void
	{
		$m = new Marker('a', 'b', 0.0, 0.0);
		$a = $m->getArray();
		Assert::false(array_key_exists('icon', $a));
	}
}


$test = new MarkerTest;
$test->run();
