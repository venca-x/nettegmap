<?php

namespace Nette\Forms;

use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

/**
 * Form control for set coordinates on map
 * Class NetteGMapPicker
 * @package Nette\Forms
 */
class NetteGMapPicker extends BaseControl {

    /**
     * Class for "Extends"
     * @var \BaseNetteGMap
     */
    private $baseNetteGMap;

    /**
     * @var boolean ShowMyActualPositionButton 
     */
    private $showMyActualPositionButton = false;

    public static function register() {
        Container::extensionMethod('addGMap', function ( Container $form, $name, $label = null ) {
            $component = new NetteGMapPicker($label);
            $form->addComponent($component, $name);
            return $component;
        });
    }

    public function __construct($label = NULL) {
        parent::__construct($label);
        $this->baseNetteGMap = new \BaseNetteGMap(array(), $this);
    }

    public function loadHttpData() {
        $data = $this->getHttpData(Form::DATA_TEXT, '[]');
        $this->value = array("latitude" => $data[0], "longitude" => $data[1]);
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getControl() {
        $container = Html::el('div', array("id" => "nette-g-map", "class" => "nettegmap-picker"));
        $container->data('map-attr', json_encode($this->getMapParams()));

        $searchBox = clone parent::getControl();
        $searchBox->type = "text";
        $searchBox->id = "nette-g-map-search-box";
        $searchBox->placeholder = "Vyhledávání";
        $container->add($searchBox);

        $container->add(Html::el('div', array("class" => "nette-g-map-canvas")));

        if( $this->showMyActualPositionButton )
        {
            $container->add( "<button type=\"button\" id=\"my-actual-position\">Načti moji polohu</button>" );
        }

        $container->add((string) $this->getTextboxControl("latitude"));
        $container->add((string) $this->getTextboxControl("longitude"));

        return $container;
    }

    public function getTextboxControl($name) {
        $control = clone parent::getControl();
        $control->type = "text";
        $control->id = $name;
        $control->name .= "[$name]";
        $control->value = $this->value[$name];

        return $control;
    }

    /*     * ************************************************************************ */

    // fake "extends BaseNetteGMap" using magic function
    public function __call($method, $args)
    {
        if(count($args) == 0) {
            return $this->baseNetteGMap->$method(array());
        } else {
            return $this->baseNetteGMap->$method($args[0]);
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
