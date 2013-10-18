nettegmap
=========

Nette addon. Viewer and picker on gmap


Configuration
-------------

bootstrap.php

```php

Nette\Forms\NetteGMapPicker::register();

```
```html
  <link rel="stylesheet" media="screen,projection,tv" href="{$basePath}/css/netteGMap.css">
  
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false"></script>
  <script type="text/javascript" src="{$basePath}/js/netteGMap.js"></script>
```

Usage viewer
-------------

```php
protected function createComponentNetteGMapViewer() {
  $markers = array();
  $markers[] = new \Marker("home", "description", "49.1695254488", "14.2521617334");
  
  $netteGMapViewer = new \NetteGMapViewer($markers);
  $netteGMapViewer->setZoom(12);
  
  return $netteGMapViewer;
}
```
```html
  {control netteGMapViewer}
```


Usage picker
-------------

```php
protected function createComponentGMapForm() {

  $form = new Nette\Application\UI\Form;

  $form->addGMap('position', 'Position:')
    ->setWidth("500")
    ->setHeight("500");

  $form->addSubmit('send', 'Save');

  $form->onSuccess[] = $this->gMapFormSucceeded;
  return $form;
}

public function gMapFormSucceeded($form) {
  $values = $form->getValues();

  dump($values);
  exit();
} 
        
```
```html
  {control gMapForm}
```

```html
  Nette\ArrayHash #f110
    position => array (2)
      latitude => "50.0923932109" (13)
      longitude => "14.4580078125" (13)
```