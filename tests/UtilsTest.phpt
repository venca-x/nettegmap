<?php

namespace Test;

use Nette,
	Tester,
	Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';


class DummyTest extends Tester\TestCase
{
	private $container;


	function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	function setUp()
	{
	}


	function testDummy()
	{
		Assert::equal( array( "gps_lat" => 50.0755381, "gps_lon" => 14.4378005), \GMapUtils::getCoordinatesFromAddress("Prague, Czech Republic") );
		Assert::same( "Náměstí Míru 820/9, 120 00 Praha-Praha 2, Czech Republic", \GMapUtils::getAddressFromCoordinates( 50.0755381, 14.4378005 ) );
	}

}


$test = new DummyTest($container);
$test->run();
