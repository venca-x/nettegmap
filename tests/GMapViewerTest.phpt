<?php

declare(strict_types=1);
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';

class GMapViewerTest extends TestCase
{
	public function setUp()
	{
	}


	public function testDummy()
	{
		//$markers = array();
		//$markers[] = new \Marker("home", "description", "49.1695254488", "14.2521617334");

		//$netteGMapViewer = new \NetteGMapViewer($markers);

		Assert::true(true);
	}
}


$test = new GMapViewerTest();
$test->run();
