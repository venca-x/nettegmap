<?php

use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

require __DIR__ . '/../src/GpsPoint.php';

class GpsPointTest extends Tester\TestCase
{
	private $container;


	function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	function setUp()
	{
	}


	function testPoint()
	{
        $point = new GpsPoint(23.123, 32);
        Assert::same(23.123, $point->getLatitude());
        Assert::same(32.0, $point->getLongitude());

        $point->setLatitude(23.124);
        $point->setLongitude(33);
        Assert::same(23.124, $point->getLatitude());
        Assert::same(33.0, $point->getLongitude());


        $point = new GpsPoint("23,123", '32,0');
        Assert::same(23.123, $point->getLatitude());
        Assert::same(32.0, $point->getLongitude());

        $point->setLatitude("23,124");
        $point->setLongitude("33,0");
        Assert::same(23.124, $point->getLatitude());
        Assert::same(33.0, $point->getLongitude());
	}

}

$test = new GpsPointTest($container);
$test->run();