<?php

namespace Nette\Forms;

use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

class NetteGMapPicker extends BaseControl {

    /**
     * Zoom of map (min: 0, max: )
     * @var int Zoom of map 
     */
    private $zoom = 12;

    /**
     * @var int X size map
     */
    private $sizeX = "500px";

    /**
     * @var int Y size map
     */
    private $sizeY = "400px";
    
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
    }

    public function loadHttpData() {
        $data = $this->getHttpData(Form::DATA_TEXT, '[]');
        $this->value = array("latitude" => $data[0], "longitude" => $data[1]);
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getControl() {
        $container = Html::el('div', array("id" => "nette-g-map"));

        $array = array(
            'size' => array(
                'x' => $this->sizeX,
                'y' => $this->sizeY,
            )
        );

        if ($this->zoom != NULL) {
            $array['zoom'] = $this->zoom;
        }

        $container->data('nette-g-map-picker', json_encode($array));

        $searchBox = clone parent::getControl();
        $searchBox->type = "text";
        $searchBox->id = "nette-g-map-search-box";
        $searchBox->placeholder = "Vyhledávání";
        $container->add($searchBox);

        $container->add(Html::el('div', array("id" => "nette-g-map-canvas")));

        $container->add((string) $this->getTextboxControl("latitude"));
        $container->add((string) $this->getTextboxControl("longitude"));
        
        if( $this->showMyActualPositionButton )
        {
            $container->add( "<button type=\"button\" id=\"my-actual-position\">Načti moji polohu</button>" );
        }
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

    /**
     * SetWidht map
     * @param string|int $width int(px), int[px], int[%]
     */
    public function setWidth($width) {
        $this->sizeX = $this->codeDimension($width);
        return $this;
    }

    /**
     * SetHeight map
     * @param string|int $height int(px), int[px], int[%]
     */
    public function setHeight($height) {
        $this->sizeY = $this->codeDimension($height);
        return $this;
    }

    /**
     * SetZoom map
     * @param int
     */
    public function setZoom($zoom) {
        $this->zoom = $zoom;
        return $this;
    }
    
    /**
     * Show button for my actual position
     */
    public function showMyActualPositionButton()
    {
        $this->showMyActualPositionButton = true;
    }

    /**
     * 
     * @param type $dimension - Dimension for verification
     * @return string - dimension
     * @throws \Exception
     */
    private function codeDimension($dimension) {

        preg_match('/(\d+)(px|%)?/i', $dimension, $matches);

        $number = $matches[1];
        $scale = @$matches[2];

        if ($scale == "") {
            //integer, add px
            return $number . "px";
        } else if ($scale == "px" ) {
            //is px
            return $number."".$scale;
        } else if ($scale == "%" ) {
            //is %
            return $number."".$scale;
        } else {
            throw new \Exception("Dimensions must be in number, px or %");
        }
    }

}
