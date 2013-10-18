<?php

class Marker extends Nette\Object {

    /** @var string */
    protected $title;

    /** @var string */
    protected $description;

    /** @var double */
    protected $latitude;

    /** @var double */
    protected $longitude;

    /** @var url */
    protected $icon;

    public function __construct($title, $description, $latitude, $longitude, $icon = null) {
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
        $this->setIcon($icon);
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setLatitude($latitude) {
        $this->latitude = $this->setDimension($latitude);
    }

    public function setLongitude($longitude) {
        $this->longitude = $this->setDimension($longitude);
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

    private function setDimension($dimension) {
        return str_replace(",", ".", $dimension);
    }

    public function getArray() {
        $array = array("title" => $this->title, "description" => $this->description, "latitude" => $this->latitude, "longitude" => $this->longitude);
        if($this->icon != null )
        {
            $array["icon"] = $this->icon;
        }
        return $array;
    }

}
