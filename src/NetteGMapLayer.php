<?php

/**
 * Class NetteGMapViewer
 */
class NetteGMapLayer extends BaseNetteGMap {

    /**
     * @var String
     * URL for layer image
     */
    private $layerUrlImage = NULL;

    /**
     * @var GpsPoint
     * Left down corner for position layer image
     */
    private $layerLeftDownCorner;

    /**
     * @var GpsPoint
     * Right top corner for position layer image
     */
    private $layerRightTopCorner;

    public function __construct($markers = array()) {
        parent::__construct($markers, $this);
    }

    public function render() {
        $template = $this->template;

        $mapParamsArray = $this->getMapParams();
        $mapParamsArray["layer"] = array(
            "layerUrlImage" => $this->layerUrlImage,
            "LeftDownCorner" => array( "lat" => $this->layerLeftDownCorner->getLatitude(), "lng" => $this->layerLeftDownCorner->getLongitude() ),
            "RightTopCorner" => array( "lat" => $this->layerRightTopCorner->getLatitude(), "lng" => $this->layerRightTopCorner->getLongitude()),
        );

        $template->json = json_encode($mapParamsArray);
        $template->setFile(__DIR__ . '/layer.latte');
        $template->render();
    }

    /**
     * @return String
     */
    public function getLayerUrlImage()
    {
        return $this->layerUrlImage;
    }

    /**
     * @param String $layerUrlImage
     */
    public function setLayerUrlImage($layerUrlImage)
    {
        $this->layerUrlImage = $layerUrlImage;
    }

    /**
     * @return GpsPoint
     */
    public function getLayerLeftDownCorner()
    {
        return $this->layerLeftDownCorner;
    }

    /**
     * @param GpsPoint $layerLeftDownCorner
     */
    public function setLayerLeftDownCorner(GpsPoint $layerLeftDownCorner)
    {
        $this->layerLeftDownCorner = $layerLeftDownCorner;
    }

    /**
     * @return GpsPoint
     */
    public function getLayerRightTopCorner()
    {
        return $this->layerRightTopCorner;
    }

    /**
     * @param GpsPoint $layerRightTopCorner
     */
    public function setLayerRightTopCorner(GpsPoint $layerRightTopCorner)
    {
        $this->layerRightTopCorner = $layerRightTopCorner;
    }

}
