<?php
declare(strict_types=1);

namespace Nette\Forms;

use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

/**
 * Form control for set coordinates on map
 * Class NetteGMapPicker
 * @package Nette\Forms
 */
class NetteGMapPicker extends BaseControl
{

	/**
	 * Class for "Extends"
	 * @var \BaseNetteGMap
	 */
	private $baseNetteGMap;

	/**
	 * @var bool ShowMyActualPositionButton
	 */
	private $showMyActualPositionButton = false;


	public static function register()
	{
		Container::extensionMethod('addGMap', function (Container $form, $name, $label = null) {
			$component = new NetteGMapPicker($label);
			$form->addComponent($component, $name);
			return $component;
		});
	}


	public function __construct($label = null)
	{
		parent::__construct($label);
		$this->baseNetteGMap = new \BaseNetteGMap([], $this);
		if ($this->baseNetteGMap->getZoom() === null) {
			$this->baseNetteGMap->setZoom(12);
		}
	}


	public function loadHttpData(): void
	{
		$data = $this->getHttpData(Form::DATA_TEXT, '[]');
		$this->value = ['latitude' => $data[0], 'longitude' => $data[1]];
	}


	public function setValue($value)
	{
		$this->value = $value;
	}


	public function getControl()
	{
		$container = Html::el('div', ['id' => 'nettegmap', 'class' => 'nettegmap-picker']);
		$container->data('map-attr', json_encode($this->getMapParams()));

		$searchBox = clone parent::getControl();
		$searchBox->type = 'text';
		$searchBox->id = 'nettegmap-search-box';
		$searchBox->placeholder = 'Vyhledávání';
		$container->addHtml($searchBox);

		$container->addHtml(Html::el('div', ['class' => 'nettegmap-canvas']));

		if ($this->showMyActualPositionButton) {
			$container->addHtml('<button type="button" id="my-actual-position">Načti moji polohu</button>');
		}

		$container->addHtml((string) $this->getTextboxControl('latitude'));
		$container->addHtml((string) $this->getTextboxControl('longitude'));

		return $container;
	}


	public function getTextboxControl($name)
	{
		$control = clone parent::getControl();
		$control->type = 'text';
		$control->id = $name;
		$control->name .= "[$name]";
		$control->value = $this->value[$name];

		return $control;
	}


	/*     * ************************************************************************ */

	// fake "extends BaseNetteGMap" using magic function
	public function __call(string $name, array $args)
	{
		if (count($args) == 0) {
			return $this->baseNetteGMap->$name([]);
		} else {
			return $this->baseNetteGMap->$name($args[0]);
		}
	}


	/**
	 * Show button for my actual position
	 */
	public function showMyActualPositionButton()
	{
		$this->showMyActualPositionButton = true;
	}
}
