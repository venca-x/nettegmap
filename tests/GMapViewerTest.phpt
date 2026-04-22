<?php

declare(strict_types=1);
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/bootstrap.php';


class GMapViewerTest extends TestCase
{
	public function testRenderAndMapParams(): void
	{
		$markers = [
			new Marker('a', 'b', 1.0, 2.0, 'https://example.com/i.png'),
		];

		$v = new NetteGMapViewer($markers);
		$v->setTemplateFactory(gmapTestTemplateFactory());
		$v->setMapId('x-map-id');
		$v->setAdvancedMarkers(true);
		$v->setScrollWheel(true);
		$v->setZoom(7);
		$v->setWidth('10px');
		$v->setHeight('50%');
		$v->setCenterMap(new GpsPoint(3.0, 4.0));

		$poly = new PolyLine([new GpsPoint(0.0, 0.0), new GpsPoint(1.0, 1.0)]);
		$v->setPolyLine($poly);

		$params = $v->getMapParams();
		Assert::true($params['map']['scrollwheel']);
		Assert::true($params['map']['advancedMarkers']);
		Assert::same('x-map-id', $params['map']['mapId']);
		Assert::same(7, $params['map']['zoom']);
		Assert::same('10px', $params['map']['size']['x']);
		Assert::same('50%', $params['map']['size']['y']);
		Assert::same(1, count($params['markers']));
		Assert::type('array', $params['polyline']);

		Assert::true($v->isScrollWheel());
		Assert::same(7, $v->getZoom());

		ob_start();
		$v->render();
		$html = (string) ob_get_clean();
		Assert::true(str_contains($html, 'nettegmap-viewer'));
		Assert::true(str_contains($html, 'x-map-id'));
	}


	public function testRenderWithoutOptionalParams(): void
	{
		$v = new NetteGMapViewer([]);
		$v->setTemplateFactory(gmapTestTemplateFactory());
		ob_start();
		$v->render();
		$html = (string) ob_get_clean();
		Assert::true(str_contains($html, 'nettegmap-canvas'));
	}


	public function testPlainNumericWidthAddsPx(): void
	{
		$v = new NetteGMapViewer([]);
		$v->setWidth('300');
		$p = $v->getMapParams();
		Assert::same('300px', $p['map']['size']['x']);
	}
}


$test = new GMapViewerTest;
$test->run();
