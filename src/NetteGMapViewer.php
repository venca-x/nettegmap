<?php

declare(strict_types=1);

/**
 * Class NetteGMapViewer
 */
class NetteGMapViewer extends BaseNetteGMap
{
	/**
	 * @param array<Marker> $markers
	 */
	public function __construct(array $markers)
	{
		parent::__construct($markers, $this);
	}


	public function render(): void
	{
		$template = $this->template;
		$template->json = json_encode($this->getMapParams());
		$template->setFile(__DIR__ . '/viewer.latte');
		$template->render();
	}
}
