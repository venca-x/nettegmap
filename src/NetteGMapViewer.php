<?php

use Nette\Utils\Html;
use Nette\Templating\Template;

class NetteGMapViewer extends Nette\Application\UI\Control {

    /**
     * Zoom of map (min: 0, max: )
     * @var int Zoom of map 
     */
    private $zoom = NULL;

    /**
     * @var int X size map
     */
    private $sizeX = "100%";

    /**
     * @var int Y size map
     */
    private $sizeY = "400px";

    /**
     * @var array $markers
     */
    private $markers;

    public function __construct($markers) {
        $this->markers = $markers;

        //convert marker object to array
        for ($i = 0; $i < count($this->markers); $i++) {
            $this->markers[$i] = $this->markers[$i]->getArray();
        }
    }

    public function render() {
        $container = Html::el('div', array("id" => "nette-g-map"));

        $array = array(
            'size' => array(
                'x' => $this->sizeX,
                'y' => $this->sizeY,
            ),
            'markers' => $this->markers,
        );
        
        if( $this->zoom != NULL )
        {
            $array['zoom'] = $this->zoom;
        }

        $container->data('nette-g-map-picker', json_encode($array));

        $container->add(Html::el('div', array("id" => "nette-g-map-canvas")));

        $template = new Template;
        $template->setSource($container);
        $template->render();
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
     * 
     * @param type $dimension - Dimension for verification
     * @return string - dimension
     * @throws \Exception
     */
    private function codeDimension($dimension) {

        if (is_int((int) $dimension)) {
            //integer, add px
            return $dimension . "px";
        } else if (is_int((int) substr($dimension, 0, -2)) && ( substr($dimension, -2) == "px" )) {
            //is px
            return $dimension;
        } else if (is_int((int) substr($dimension, 0, -1)) && ( substr($dimension, -1) == "%" )) {
            //is %
            return $dimension;
        } else {
            throw new \Exception("Dimensions must be in number, px or %");
        }
    }

}
