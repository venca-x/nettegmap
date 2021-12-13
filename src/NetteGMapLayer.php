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
	private string $layerUrlImage;

	/**
	 * @var GpsPoint
	 * Left down corner for position layer image
	 */
	private GpsPoint $layerLeftDownCorner;

	/**
	 * @var GpsPoint
	 * Right top corner for position layer image
	 */
	private GpsPoint $layerRightTopCorner;


	/**
	 * @param array<Marker> $markers
	 */
	public function __construct(array $markers = [])
	{
		parent::__construct($markers, $this);
	}


	public function render(): void
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


	public function getLayerUrlImage(): string
	{
		return $this->layerUrlImage;
	}


	public function setLayerUrlImage(string $layerUrlImage): void
	{
		$this->layerUrlImage = $layerUrlImage;
	}


	public function getLayerLeftDownCorner(): GpsPoint
	{
		return $this->layerLeftDownCorner;
	}


	public function setLayerLeftDownCorner(GpsPoint $layerLeftDownCorner): void
	{
		$this->layerLeftDownCorner = $layerLeftDownCorner;
	}


	public function getLayerRightTopCorner(): GpsPoint
	{
		return $this->layerRightTopCorner;
	}


	public function setLayerRightTopCorner(GpsPoint $layerRightTopCorner): void
	{
		$this->layerRightTopCorner = $layerRightTopCorner;
	}
}
