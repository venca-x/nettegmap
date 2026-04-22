<?php

declare(strict_types=1);
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';

final class GMapUtilsTestable extends GMapUtils
{
	public static ?string $httpResponse = null;


	public static function setHttpResponse(?string $json): void
	{
		self::$httpResponse = $json;
	}


	protected static function httpGet(string $url): string
	{
		if (self::$httpResponse !== null) {
			return self::$httpResponse;
		}

		return parent::httpGet($url);
	}
}


class GMapUtilsTest extends TestCase
{
	public function setUp(): void
	{
		GMapUtilsTestable::setHttpResponse(null);
	}


	public function testGetCoordinatesFromAddressSuccess(): void
	{
		GMapUtilsTestable::setHttpResponse(json_encode([
			'results' => [
				[
					'geometry' => [
						'location' => ['lat' => 1.5, 'lng' => 2.5],
					],
				],
			],
		]));

		$r = GMapUtilsTestable::getCoordinatesFromAddress('Test Street');
		Assert::same(['gps_lat' => 1.5, 'gps_lon' => 2.5], $r);
	}


	public function testGetCoordinatesFromAddressErrorMessage(): void
	{
		GMapUtilsTestable::setHttpResponse(json_encode(['error_message' => 'API down']));

		Assert::exception(
			fn() => GMapUtilsTestable::getCoordinatesFromAddress('x'),
			Throwable::class,
			'API down',
		);
	}


	public function testGetCoordinatesFromAddressNoResults(): void
	{
		GMapUtilsTestable::setHttpResponse(json_encode(['results' => []]));

		Assert::exception(
			fn() => GMapUtilsTestable::getCoordinatesFromAddress('x'),
			Throwable::class,
			"Can't search address: x",
		);
	}


	public function testGetAddressFromCoordinatesSuccess(): void
	{
		GMapUtilsTestable::setHttpResponse(json_encode([
			'results' => [
				['formatted_address' => 'Somewhere 1'],
			],
		]));

		Assert::same('Somewhere 1', GMapUtilsTestable::getAddressFromCoordinates(1.0, 2.0));
	}


	public function testGetAddressFromCoordinatesErrorMessage(): void
	{
		GMapUtilsTestable::setHttpResponse(json_encode(['error_message' => 'no']));

		Assert::exception(
			fn() => GMapUtilsTestable::getAddressFromCoordinates(1.0, 2.0),
			Throwable::class,
			'no',
		);
	}


	public function testGetAddressFromCoordinatesNoFormattedAddress(): void
	{
		GMapUtilsTestable::setHttpResponse(json_encode(['results' => [[]]]));

		Assert::exception(
			fn() => GMapUtilsTestable::getAddressFromCoordinates(1.0, 2.0),
			Throwable::class,
			"Can't search address for GPS: 1, 2",
		);
	}


	public function testHttpGetFailure(): void
	{
		$method = new ReflectionMethod(GMapUtils::class, 'httpGet');
		$method->setAccessible(true);
		$badFile = 'file:///' . str_replace('\\', '/', __DIR__) . '/___gmap_test_missing_' . uniqid('', true) . '_.tmp';
		Assert::exception(
			static function () use ($method, $badFile): void {
				$method->invoke(null, $badFile);
			},
			Throwable::class,
		);
	}


	public function testHttpGetReadsExistingFile(): void
	{
		$method = new ReflectionMethod(GMapUtils::class, 'httpGet');
		$method->setAccessible(true);
		$tmp = tempnam(sys_get_temp_dir(), 'gmapu');
		Assert::truthy($tmp);
		file_put_contents($tmp, 'ok-content');
		$uri = 'file:///' . str_replace('\\', '/', $tmp);
		Assert::same('ok-content', $method->invoke(null, $uri));
		@unlink($tmp);
	}
}

$test = new GMapUtilsTest;
$test->run();
