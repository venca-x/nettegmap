<?php

/**
 * Class Point
 * GPS point
 */
class GpsPoint {
	use Nette\SmartObject;

    /** @var double */
    public $latitude;

    /** @var double */
    public $longitude;

    public function __construct($latitude, $longitude) {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
    }

    private function setDimension($dimension) {
        return str_replace(",", ".", $dimension);
    }

    /**
     * @return Double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = (Double) $this->setDimension( $latitude );
    }

    /**
     * @return Double Double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = (Double) $this->setDimension( $longitude );
    }



}
