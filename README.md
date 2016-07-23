Nettegmap
===============
[![Build Status](https://travis-ci.org/venca-x/nettegmap.svg)](https://travis-ci.org/venca-x/nettegmap) 
[![Latest Stable Version](https://poser.pugx.org/venca-x/nettegmap/v/stable.svg)](https://packagist.org/packages/venca-x/nettegmap) 
[![Total Downloads](https://poser.pugx.org/venca-x/nettegmap/downloads.svg)](https://packagist.org/packages/venca-x/nettegmap) 
[![Latest Unstable Version](https://poser.pugx.org/venca-x/nettegmap/v/unstable.svg)](https://packagist.org/packages/venca-x/nettegmap) 
[![License](https://poser.pugx.org/venca-x/nettegmap/license.svg)](https://packagist.org/packages/venca-x/nettegmap)


Nette addon. Viewer and picker for Google maps

Installation
------------

Install with composer:
```
composer require venca-x/nettegmap:~1.1
```
You need use jQuery.

Configuration
-------------

bootstrap.php add register line OR add line in config.neon

```php
Nette\Forms\NetteGMapPicker::register();//require only form picker
```

OR add line to config.neon:
```
extensions:
    nettegmap: Nette\Forms\NetteGMapPicker
```

```html
<link rel="stylesheet" media="screen,projection,tv" href="{$basePath}/css/netteGMap.css">
  
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&amp;sensor=false"></script>
<script type="text/javascript" src="{$basePath}/js/jquery.netteGMap.js"></script>
<script type="text/javascript" src="{$basePath}/js/main.js"></script>
```

## Callback after change position marker in main.js
main.js
```js
$( function() {
    $( 'body' ).netteGMapPicker( {
    
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

Usage with Bower
-------------
```js
concat: {
    js: {
        src: ['bower_components/jquery/dist/jquery.min.js',
            'vendor/nette/forms/src/assets/netteForms.js',
            'bower_components/bootstrap/dist/js/bootstrap.min.js',
            'vendor/venca-x/nettegmap/client/js/jquery.netteGMap.js',
            'www/js/main.js'
        ],
        dest: 'www/js/compiled.min.js'
    }
},
cssmin: {
    target: {
        files: {
            'www/css/main.min.css': ['www/css/main.css','vendor/venca-x/nettegmap/client/css/netteGMap.css']
        }
    }
}
```

Usage viewer
-------------
This example show how to show map with marker:

```php
protected function createComponentNetteGMapViewer() {
    $markers = array();
    $markers[] = new \Marker("home", "description", "49.1695254488", "14.2521617334");
    
    //$netteGMapViewer->setCenterMap(new \GpsPoint(49.1695254488,14.2521617334));
    //$netteGMapViewer->setScrollwheel(TRUE);
    $netteGMapViewer = new \NetteGMapViewer($markers);
    //$netteGMapViewer->setZoom(12);
    
    
    //add polyline to map
    $coordinates = array(
        new \GpsPoint(49.169669, 14.252152),
        new \GpsPoint(49.169399, 14.252175),
        new \GpsPoint(49.169532, 14.251842),
        new \GpsPoint(49.169669, 14.252152)
    );
    $polyLine = new \PolyLine($coordinates);
    $netteGMapViewer->setPolyLine($polyLine);
          
    return $netteGMapViewer;
}
```

```html
{control netteGMapViewer}
```


Usage picker
-------------
This example show how to set GPS position on map in form:

```php
protected function createComponentGMapForm() {

    $form = new Nette\Application\UI\Form;
    
    $form->addGMap('position', 'Position:')
        ->setWidth("500")
        ->setHeight("500");
        //->showMyActualPositionButton();
        //$netteGMapViewer->setCenterMap(new \GpsPoint(49.1695254488,14.2521617334));
        //$netteGMapViewer->setScrollwheel(TRUE);
    
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

Set default position value for picker:
```php
$form->setDefaults(array(
    'position' => array(
        'latitude' => "49.1695254488",
        'longitude' => "14.2521617334",
    ),
));
```

Latte:
```html
{control gMapForm}
```

After send form:
```html
Nette\ArrayHash #f110
    position => array (2)
        latitude => "50.0923932109" (13)
        longitude => "14.4580078125" (13)
```

Usage layer
-------------
This example show how to add own picture on map:

protected function createComponentNetteGMapLayer() {
    $netteGMapViewer = new \NetteGMapLayer();
    //$netteGMapViewer->setCenterMap(new \GpsPoint("48.977153", "14.454486"));
    $netteGMapViewer->setHeight("600px");
    $netteGMapViewer->setScrollwheel(TRUE);
    $netteGMapViewer->setZoom(18);
    
    $netteGMapViewer->setLayerUrlImage("http://www.barcampjc.cz/pictures/parkoviste.jpg");
    
    $netteGMapViewer->setLayerLeftDownCorner(new \GpsPoint(48.97685376928403, 14.453693823169715));
    $netteGMapViewer->setLayerRightTopCorner(new \GpsPoint(48.97771464665134, 14.45583921230309));
    
    return $netteGMapViewer;
}

Latte:
```html
{control netteGMapLayer}
```
----------------------------------------------------------------------------------------------------

Get coordinates from address
-------------
```php
\GMapUtils::getCoordinatesFromAddress("Prague, Czech Republic")
```	
return 
```php
array( "gps_lat" => 50.0755381, "gps_lon" => 14.4378005)
```

Get address from coordinates
-------------
```php
\GMapUtils::getAddressFromCoordinates( 50.0755381, 14.4378005 )
```	
return
```
Náměstí Míru 820/9, 120 00 Praha-Praha 2, Czech Republic
```

Set marker position from out script (JS)
-------------
```js
$( "#my-div-map div.nettegmap-canvas" ).setMarkerPosition( 14.1111, 48.2222 );
```

Limits looking coordinates
-------------
Users of the free API:
2,500 requests per 24 hour period.
5 requests per second.
