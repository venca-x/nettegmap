<?php

/**
 * Class AbstractNetteGMap
 * Base abstract map class for propertis in map
 */
class BaseNetteGMap extends Nette\Application\UI\Control {

    private $child = NULL;

    /**
     * Zoom of map (min: 0, max: )
     * @var int Zoom of map 
     */
    private $zoom = 12;

    /**
     * @var int X size map
     */
    private $sizeX = "100%";

    /**
     * @var int Y size map
     */
    private $sizeY = "400px";

    /**
     * @var GpsPoint
     * Center of map
     */
    private $centerMapGpsPoint = NULL;

    /**
     * @var bool Zoom map with scroll wheal in mouse
     */
    private $scrollwheel = FALSE;

    /**
     * @var array $markers
     */
    private $markers;

    /**
     * @var PolyLine $polyLine
     */
    private $polyLine = NULL;

    public function __construct($markers, $child) {
        $this->markers = $markers;
        $this->child = $child;

        //convert marker object to array
        for ($i = 0; $i < count($this->markers); $i++) {
            $this->markers[$i] = $this->markers[$i]->getArray();
        }
    }

    public function getMapParams() {
        $array = array();

        $array['map'] = array(
            'size' => array(
                'x' => $this->sizeX,
                'y' => $this->sizeY,
            ),
            'scrollwheel' => $this->scrollwheel
        );

        if($this->centerMapGpsPoint != NULL) {
            $array['map']['center'] = array(
                'lat' => $this->centerMapGpsPoint->getLongitude(),
                'lng' => $this->centerMapGpsPoint->getLatitude(),
            );
        }

        if( $this->zoom != NULL )
        {
            $array['map']['zoom'] = $this->zoom;
        }


        if(count($this->markers) > 0) {
            $array['markers'] = $this->markers;
        }

        if($this->polyLine != NULL) {
            $array['polyline'] = $this->polyLine->getArray();
        }

        return $array;
    }

    /**
     * Add Poly line to map
     * @param PolyLine $polyLine
     */
    public function setPolyLine(PolyLine $polyLine) {
        $this->polyLine = $polyLine;
    }

    /*     * ************************************************************************ */

    /**
     * @param GpsPoint $centerMap
     * Set hard center of map, if is null is set automatic from markers
     */
    public function setCenterMap(GpsPoint $centerMap) {
        $this->centerMapGpsPoint = $centerMap;
    }

    /**
     * SetWidht map
     * @param string|int $width int(px), int[px], int[%]
     */
    public function setWidth($width) {
        $this->sizeX = $this->codeDimension($width);
        return $this->child;
    }

    /**
     * SetHeight map
     * @param string|int $height int(px), int[px], int[%]
     */
    public function setHeight($height) {
        $this->sizeY = $this->codeDimension($height);
        return $this->child;
    }

    /**
     * SetZoom map
     * @param int
     */
    public function setZoom($zoom) {
        $this->zoom = $zoom;
        return $this->child;
    }

    /**
     * @return boolean
     */
    public function isScrollwheel()
    {
        return $this->scrollwheel;
    }

    /**
     * @param boolean $scrollwheel
     */
    public function setScrollwheel($scrollwheel)
    {
        $this->scrollwheel = (boolean) $scrollwheel;
        return $this->child;
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
