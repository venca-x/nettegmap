<?php

declare(strict_types=1);
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';


class GMapLayerTest extends TestCase
{
	public function testLayerRenderAndGetters(): void
	{
		$layer = new NetteGMapLayer;
		$ld = new GpsPoint(10.0, 20.0);
		$rt = new GpsPoint(30.0, 40.0);

		$layer->setLayerUrlImage('https://example.com/map.png');
		$layer->setLayerLeftDownCorner($ld);
		$layer->setLayerRightTopCorner($rt);
		$layer->setZoom(5);

		Assert::same('https://example.com/map.png', $layer->getLayerUrlImage());
		Assert::same($ld, $layer->getLayerLeftDownCorner());
		Assert::same($rt, $layer->getLayerRightTopCorner());

		$layer->setTemplateFactory(gmapTestTemplateFactory());

		ob_start();
		$layer->render();
		$html = (string) ob_get_clean();

		Assert::true(str_contains($html, 'nettegmap'));
		Assert::true(str_contains($html, 'map.png'));
	}
}


$test = new GMapLayerTest;
$test->run();
