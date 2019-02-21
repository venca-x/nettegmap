<?php
declare(strict_types=1);
use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

class GMapUtilsTest extends Tester\TestCase
{
	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function setUp()
	{
	}


	public function testDummy()
	{
		Assert::same(true, true);
		//Assert::equal(['gps_lat' => 40.6898059, 'gps_lon' => -74.0450227], \GMapUtils::getCoordinatesFromAddress('1 Liberty Island - Ellis Island, New York, NY 10004, USA'));
		//Assert::same('1 Liberty Island - Ellis Island, New York, NY 10004, USA', \GMapUtils::getAddressFromCoordinates(40.6892494, -74.0445004));
	}
}


$test = new GMapUtilsTest($container);
$test->run();
