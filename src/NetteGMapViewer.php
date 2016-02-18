<?php

use Nette\Utils\Html;
use Nette\Application\UI\Control;

/**
 * Class NetteGMapViewer
 */
class NetteGMapViewer extends Control {

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

        $template = $this->template;
        $template->json = json_encode($array);
        $template->setFile(__DIR__ . '/viewer.latte');
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
