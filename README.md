nettegmap
=========

Nette addon. Viewer and picker on gmap

Installation
------------

 1. Add the bundle to your dependencies:

        // composer.json

        {
           // ...
           "require": {
               // ...
			   "venca-x/nettegmap": "1.0.0"
           }
        }

 2. Use Composer to download and install the bundle:

        composer update
        

Or: 
		composer require venca-x/nettegmap

Configuration
-------------

bootstrap.php

```php

Nette\Forms\NetteGMapPicker::register();

```
```html
  <link rel="stylesheet" media="screen,projection,tv" href="{$basePath}/css/netteGMap.css">
  
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false"></script>
  <script type="text/javascript" src="{$basePath}/js/jquery.netteGMap.js"></script>
  <script type="text/javascript" src="{$basePath}/js/main.js"></script>
```

main.js
```html
$( function() {
    $( 'body' ).netteGMap( {
	
        //my callback marker change position
        changePositionMarker: function( results ) {

            var district = results[4].formatted_address.split(",");
            //alert( district[0] );
            $("select#frm-addCompetitionForm-district_id option").each(function() { this.selected = ( this.text === district[0] ); });
            $("select#frm-editCompetitionForm-district_id option").each(function() { this.selected = ( this.text === district[0] ); });
            //alert('changePositionMarker');
        }
        
    } );	
} );
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
	//->showMyActualPositionButton();

  $form->addSubmit('send', 'Save');

  $form->onSuccess[] = $this->gMapFormSucceeded;
  return $form;
}

public function gMapFormSucceeded($form) {
  $values = $form->getValues();

  dump($values);
  exit();
} 
```php

```php
	$form->setDefaults(array(
		'position' => array(
			'latitude' => "49.1695254488",
			'longitude' => "14.2521617334",
		),
	));
```php

```html
  {control gMapForm}
```

```html
  Nette\ArrayHash #f110
    position => array (2)
      latitude => "50.0923932109" (13)
      longitude => "14.4580078125" (13)
```

----------------------------------------------------------------------------------------------------

Get coordinates from address
-------------

	\GMapUtils::getCoordinatesFromAddress("Prague, Czech Republic")
	
return 

	array( "gps_lat" => 50.0755381, "gps_lon" => 14.4378005)


Get address from coordinates
-------------

	\GMapUtils::getAddressFromCoordinates( 50.0755381, 14.4378005 )
	
return

	Náměstí Míru 820/9, 120 00 Praha-Praha 2, Czech Republic

Limits looking coordinates
-------------

Users of the free API:
2,500 requests per 24 hour period.
5 requests per second.