<?php
declare(strict_types=1);
/**
 * Class NetteGMapViewer
 */
class NetteGMapLayer extends BaseNetteGMap
{

	/**
	 * @var string
	 * URL for layer image
	 */
	private $layerUrlImage;

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


	public function __construct($markers = [])
	{
		parent::__construct($markers, $this);
	}


	public function render()
	{
		$template = $this->template;

		$mapParamsArray = $this->getMapParams();
		$mapParamsArray['layer'] = [
			'layerUrlImage' => $this->layerUrlImage,
			'LeftDownCorner' => ['lat' => $this->layerLeftDownCorner->getLatitude(), 'lng' => $this->layerLeftDownCorner->getLongitude()],
			'RightTopCorner' => ['lat' => $this->layerRightTopCorner->getLatitude(), 'lng' => $this->layerRightTopCorner->getLongitude()],
		];

		$template->json = json_encode($mapParamsArray);
		$template->setFile(__DIR__ . '/layer.latte');
		$template->render();
	}


	/**
	 * @return string
	 */
	public function getLayerUrlImage()
	{
		return $this->layerUrlImage;
	}


	/**
	 * @param string $layerUrlImage
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


	public function setLayerRightTopCorner(GpsPoint $layerRightTopCorner)
	{
		$this->layerRightTopCorner = $layerRightTopCorner;
	}
}
