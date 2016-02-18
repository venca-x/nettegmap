<?php

/**
 * Class NetteGMapViewer
 */
class NetteGMapLayer extends BaseNetteGMap {


    public function __construct($markers) {
        parent::__construct($markers, $this);
    }

    public function render() {
        $template = $this->template;
        $template->json = json_encode($this->getMapParams());
        $template->setFile(__DIR__ . '/layer.latte');
        $template->render();
    }

}
