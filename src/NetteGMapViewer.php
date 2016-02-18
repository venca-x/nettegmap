<?php

/**
 * Class NetteGMapViewer
 */
class NetteGMapViewer extends BaseNetteGMap {

    public function __construct($markers) {
        parent::__construct($markers, $this);
    }

    public function render() {
        $template = $this->template;
        $template->json = json_encode($this->getMapParams());
        $template->setFile(__DIR__ . '/viewer.latte');
        $template->render();
    }

}
