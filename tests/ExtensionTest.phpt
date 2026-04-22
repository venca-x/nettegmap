<?php

declare(strict_types=1);

namespace Test;

use Nette;
use Nette\Forms\Form;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

final class NetteGMapPickerHttpStub extends Nette\Forms\NetteGMapPicker
{
	protected function getHttpData($type, ?string $htmlTail = null): array
	{
		return ['50.1', '14.2'];
	}
}


class ExtensionTest extends Tester\TestCase
{
	public function setUp()
	{
	}


	public function testRegistrationExtension()
	{
		Nette\Forms\NetteGMapPicker::register();

		$form = new Form;

		$mapControl = $form->addGMap('position', 'Position:')
			->setWidth('500')
			->setHeight('500');

		Assert::type(Nette\Forms\NetteGMapPicker::class, $mapControl);
		Assert::same('<div id="nettegmap" class="nettegmap-picker" data-map-attr=\'{"map":{"size":{"x":"500px","y":"500px"},"scrollwheel":false,"advancedMarkers":false,"zoom":12}}\'><input type="text" name="position" id="nettegmap-search-box" placeholder="Vyhledávání"><div class="nettegmap-canvas"></div><input type="text" name="position[latitude]" id="latitude"><input type="text" name="position[longitude]" id="longitude"></div>', (string) $mapControl->getControl());
	}


	public function testPickerDefaultValues()
	{
		Nette\Forms\NetteGMapPicker::register();

		$form = new Form;

		$mapControl = $form->addGMap('position', 'Position:')
			->setWidth('600')
			->setHeight('600');

		$defaultValues = ['position' => [
			'latitude' => '49.1695254488',
			'longitude' => '14.2521617334',
		]];

		$form->setDefaults($defaultValues);

		Assert::type(Nette\Forms\NetteGMapPicker::class, $mapControl);
		Assert::same('<div id="nettegmap" class="nettegmap-picker" data-map-attr=\'{"map":{"size":{"x":"600px","y":"600px"},"scrollwheel":false,"advancedMarkers":false,"zoom":12}}\'><input type="text" name="position" id="nettegmap-search-box" placeholder="Vyhledávání"><div class="nettegmap-canvas"></div><input type="text" name="position[latitude]" id="latitude" value="49.1695254488"><input type="text" name="position[longitude]" id="longitude" value="14.2521617334"></div>', (string) $mapControl->getControl());
	}


	public function testLoadHttpData(): void
	{
		$picker = new NetteGMapPickerHttpStub('L');
		$picker->loadHttpData();
		Assert::same(['latitude' => '50.1', 'longitude' => '14.2'], $picker->getValue());
	}


	public function testMyActualPositionButton(): void
	{
		Nette\Forms\NetteGMapPicker::register();

		$form = new Form;
		$mapControl = $form->addGMap('p', 'L');
		$mapControl->showMyActualPositionButton();
		$html = (string) $mapControl->getControl();
		Assert::true(str_contains($html, 'my-actual-position'));
	}


	public function testSetMapIdInDataAttr(): void
	{
		Nette\Forms\NetteGMapPicker::register();

		$form = new Form;
		$mapControl = $form->addGMap('pos', 'L')->setMapId('gmap-id-99');
		Assert::true(str_contains((string) $mapControl->getControl(), 'gmap-id-99'));
	}


	public function testMagicGetZoomForwardsToBase(): void
	{
		Nette\Forms\NetteGMapPicker::register();

		$form = new Form;
		$mapControl = $form->addGMap('p', 'L');
		Assert::same(12, $mapControl->getZoom());
	}
}

$test = new ExtensionTest;
$test->run();
