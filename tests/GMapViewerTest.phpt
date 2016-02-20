<?php

use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

class GMapViewerTest extends Tester\TestCase
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
        //$markers = array();
        //$markers[] = new \Marker("home", "description", "49.1695254488", "14.2521617334");

        //$netteGMapViewer = new \NetteGMapViewer($markers);

        Assert::true(TRUE);
	}

}


$test = new GMapViewerTest($container);
$test->run();
