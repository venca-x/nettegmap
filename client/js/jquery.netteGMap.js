$( document ).ready( function() {

    ( function( $ ) {

        var markers = []; //array of markers
        var infowindow = new google.maps.InfoWindow();

        function initializeMap(thisMap, dataMapAttr) {

            var mapProp = {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: dataMapAttr.map.scrollwheel,
            };

            if(typeof dataMapAttr.map.center !== 'undefined') {
                mapProp.center = new google.maps.LatLng(dataMapAttr.map.center.lng, dataMapAttr.map.center.lat);
            }

            if ( typeof dataMapAttr.map.zoom !== 'undefined' ) {
                mapProp.zoom = dataMapAttr.map.zoom;
            }

            var mapCanvasDiv = thisMap.find("div.nette-g-map-canvas");

            //set dimensions of map
            mapCanvasDiv.width( dataMapAttr.map.size.x );
            mapCanvasDiv.height( dataMapAttr.map.size.y );

            var map = new google.maps.Map(mapCanvasDiv[0], mapProp);

            return map;
        }

        $.fn.netteGMapViewer = function() {

            var dataMapAttr = this.data( "map-attr" );
            if(typeof dataMapAttr !== 'undefined') {

                var map = initializeMap(this, dataMapAttr);

                /////////////////////////////////////////////////////////////////////////
                //viewer - markers
                bounds = new google.maps.LatLngBounds();

                //view markers
                for (i = 0; i < dataMapAttr.markers.length; i++) {
                    addMarkerToMap(map, bounds, dataMapAttr.markers[i], i);
                }

                if (typeof dataMapAttr.map.center !== 'undefined') {
                    //is set center
                    mapProp.center = new google.maps.LatLng(dataMapAttr.map.center.lng, dataMapAttr.map.center.lat);
                }

                if (typeof dataMapAttr.map.zoom !== 'undefined') {
                    //set zoom and center auto - bounds
                    map.fitBounds(bounds);
                }

                //////////////////////////////////////////////////////////////////////////
            }
            return this;
        }

        $.fn.netteGMapLayer = function() {
            var dataMapAttr = this.data("map-attr");

            if(typeof dataMapAttr !== 'undefined') {

                var map = initializeMap(this, dataMapAttr);

                /////////////////////////////////////////////////////////////////////////////////////////////////
                MapOverlay.prototype = new google.maps.OverlayView();

                var swBound = new google.maps.LatLng(dataMapAttr.layer.LeftDownCorner.lat, dataMapAttr.layer.LeftDownCorner.lng);
                var neBound = new google.maps.LatLng(dataMapAttr.layer.RightTopCorner.lat, dataMapAttr.layer.RightTopCorner.lng);
                var bounds = new google.maps.LatLngBounds(swBound, neBound);

                if (typeof dataMapAttr.map.center === 'undefined') {
                    //is not defined center of map, use auto center from bounds
                    map.fitBounds(bounds);
                }

                var srcImage = dataMapAttr.layer.layerUrlImage;

                new MapOverlay(bounds, srcImage, map);

                /////////////////////////////////////////////////////////////////////////////////////////////////
            }

            return this;
        }

        $.fn.netteGMapPicker = function( options ) {

            var dataMapAttr = this.data("map-attr");

            if(typeof dataMapAttr !== 'undefined') {

                // Set defaults
                defaults = {
                    changePositionMarker: function( results ) {}
                };

                // Override defaults with options
                options = $.extend( defaults, options );

                //in search is key ENTER none function
                $( "#nette-g-map-search-box" ).keydown( function( e ) {
                    //@TODO predelat na this
                    if ( e.keyCode == 13 ) {
                        //stisknut ENTER, neodesilej formular
                        return false;
                    }
                } );

                //search
                var searchBox = new google.maps.places.SearchBox( $( "#nette-g-map-search-box" )[0] );

                //search result
                google.maps.event.addListener( searchBox, 'places_changed', function() {

                    var places = searchBox.getPlaces();

                    //set first marker from places
                    if ( places.length > 0 ) {
                        changeMarkerPickerLocation( map, places[0].geometry.location );
                    }
                } );

                var map = initializeMap(this, dataMapAttr);

                //set picker marker
                setMarkerPicker( map, new google.maps.LatLng( $( "input#latitude" ).val(), $( "input#longitude" ).val() ) );
            }

            return this;
        }


        $( "div.nettegmap-viewer" ).netteGMapViewer();
        $( "div.nettegmap-layer" ).netteGMapLayer();
        $( "div.nettegmap-picker" ).netteGMapPicker();


        ////////////////////////////////////////////////////////////////////////////////////////////////////
        function addMarkerToMap(map, bounds, marker, i ) {

            var location = new google.maps.LatLng( marker.latitude, marker.longitude );

            //create marker
            var markerPoint = new google.maps.Marker( {
                position: location,
                map: map,
                draggable: false,
                title: marker.title
            } );

            //set icon whenn is set
            if ( typeof marker.icon !== 'undefined' ) {
                markerPoint.setIcon( marker.icon );
            }

            google.maps.event.addListener( markerPoint, 'click', ( function( markerPoint, i ) {
                return function() {
                    var content = "";
                    if(marker.title != "") {
                        content = content + "<strong>" + marker.title + "</strong><br/>";
                    }
                    content = content + "" + marker.description;
                    infowindow.setContent( content );
                    infowindow.open( map, markerPoint );
                };
            } )( markerPoint, i ) );

            bounds.extend( location );

            //add marker to array
            markers.push( markerPoint );
        }

        /**
         * Set position of marker picker
         * @param map
         * @param location
         */
        function setMarkerPicker( map, location ) {
            var markerPoint = new google.maps.Marker( {
                position: location,
                map: map,
                draggable: true
            } );

            map.setCenter( location );
            markers.push( markerPoint );

            changeMarkerPickerLocation(map, location );
        }

        /**
         * Calbback after change marker picker position
         * @param map
         * @param location
         */
        function changeMarkerPickerLocation(map, location )
        {
            markers[0].setPosition( location );
            map.setCenter( location );

            //set position marker to inputs
            $( "input#latitude" ).val( markers[0].position.lat() );
            $( "input#longitude" ).val( markers[0].position.lng() );


            google.maps.event.addListener( markers[0], 'dragend', function( point ) {

                $( "input#latitude" ).val( point.latLng.lat().toFixed( 10 ) );
                $( "input#longitude" ).val( point.latLng.lng().toFixed( 10 ) );

                //getGeocodeData( new google.maps.LatLng( markers[0].position.lat(), markers[0].position.lng() ) );
                getGeocodeData( new google.maps.LatLng( point.latLng.lat(), point.latLng.lng() ) );
            } );
        }

        function getGeocodeData( latLng )
        {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': latLng}, function(results, status) {
                if ( status === google.maps.GeocoderStatus.OK ) {
                    defaults.changePositionMarker( results );//call function to result data geocode
                }
            });
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////

        // map layer - mapoverlay >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

        /** @constructor */
        function MapOverlay(bounds, image, map) {

            this.bounds_ = bounds;
            this.image_ = image;

            this.div_ = null;

            this.setMap(map);
        }

        MapOverlay.prototype.draw = function() {
            var overlayProjection = this.getProjection();
            var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());//spodni leva
            var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());//horni prava

            var div = this.div_;
            div.style.left = sw.x + 'px';
            div.style.top = ne.y + 'px';
            div.style.width = (ne.x - sw.x) + 'px';
            div.style.height = (sw.y - ne.y) + 'px';
        };

        MapOverlay.prototype.onRemove = function() {
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        };

        MapOverlay.prototype.onAdd = function() {
            var div = document.createElement('div');
            div.style.borderStyle = 'none';
            div.style.borderWidth = '0px';
            div.style.position = 'absolute';

            var img = document.createElement('img');
            img.src = this.image_;
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.position = 'absolute';
            div.appendChild(img);

            this.div_ = div;

            var panes = this.getPanes();
            panes.overlayLayer.appendChild(div);
        };

        // map layer - mapoverlay <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

/*
        $("button#my-actual-position").click(function() {
            showMyPosition();
        });

        $.fn.netteGMap = function( options ) {
           
            //exist NetteGMap?
            if ( $( '#nette-g-map' ).length === 1 ) {

                infowindow = new google.maps.InfoWindow();
                geocoder = new google.maps.Geocoder();

                // Set defaults
                defaults = {
                    changePositionMarker: function( results ) {}
                };

                // Override defaults with options
                options = $.extend( defaults, options );


				//pri zadavani vyhledavane fraze do naseptace v mape, neukladat cely form na klavesu enter
				$( "#nette-g-map-search-box" ).keydown( function( e ) {
					if ( e.keyCode == 13 ) {
						//stisknut ENTER, neodesilej formular
						return false;
					}
				} );				
				
                //get data atributes
                var dataAttr = $( '#nette-g-map' ).data( "nette-g-map-picker" );

                map = new google.maps.Map( $( '#nette-g-map-canvas' )[0], {
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoom: dataAttr.zoom,
                    scrollwheel: false
                } );

                //set dimensions of map
                $( "#nette-g-map-canvas" ).width( dataAttr.size.x );
                $( "#nette-g-map-canvas" ).height( dataAttr.size.y );
                //////////////////////////////////

                markers = []; //array of markers

                if ( $( '#nette-g-map #nette-g-map-search-box' ).length === 1 )
                {
                    //picker
                    
                    //set default marker
                    setMarkerPicker( new google.maps.LatLng( $( "input#latitude" ).val(), $( "input#longitude" ).val() ) );                    
                    
                    //search
                    var searchBox = new google.maps.places.SearchBox( $( "#nette-g-map-search-box" )[0] );

                    //search result
                    google.maps.event.addListener( searchBox, 'places_changed', function() {

                        var places = searchBox.getPlaces();

                        //set first marker from places
                        if ( places.length > 0 ) {
                            changeMarkerPickerLocation( places[0].geometry.location );
                        }
                    } );
                }
                else
                {
                    //viewer
                    bounds = new google.maps.LatLngBounds();

                    //view markers
                    for ( i = 0; i < dataAttr.markers.length; i++ )
                    {
                        setMarkerViewer( dataAttr.markers[i], i );
                    }

                    if ( typeof dataAttr.zoom === 'undefined' ) {
                        //set zoom and center auto - bounds
                        map.fitBounds( bounds );
                    }
                    else
                    {
                        //is defined zoom, set center map
                        //set center from first marker
                        map.setCenter( new google.maps.LatLng( dataAttr.markers[0].latitude, dataAttr.markers[0].longitude ) );
                    }
                }                
            }
        };

        $.fn.setMarkerPosition = function(lon, lat) {
            var location = new google.maps.LatLng( lat, lon );
            changeMarkerPickerLocation( location );
        };
        
        function setMarkerPicker( location ) {
            markerPoint = new google.maps.Marker( {
                position: location,
                map: map,
                draggable: true
            } );

            map.setCenter( location );
            markers.push( markerPoint );

            changeMarkerPickerLocation( location );
        }

        function setMarkerViewer( marker, i ) {

            var location = new google.maps.LatLng( marker.latitude, marker.longitude );

            //create marker
            markerPoint = new google.maps.Marker( {
                position: location,
                map: map,
                draggable: false,
                title: marker.title
            } );

            //set icon whenn is set
            if ( typeof marker.icon !== 'undefined' ) {
                markerPoint.setIcon( marker.icon );
            }

            google.maps.event.addListener( markerPoint, 'click', ( function( markerPoint, i ) {
                return function() {
                    infowindow.setContent( marker.description );
                    infowindow.open( map, markerPoint );
                };
            } )( markerPoint, i ) );

            bounds.extend( location );

            //add marker to array
            markers.push( markerPoint );
        }

        //Change location MarkerPicker
        function changeMarkerPickerLocation( location )
        {
            markers[0].setPosition( location );
            map.setCenter( location );

            //set position marker to inputs
            $( "input#latitude" ).val( markerPoint.position.lat() );
            $( "input#longitude" ).val( markerPoint.position.lng() );

            google.maps.event.addListener( markerPoint, 'dragend', function( point ) {

                $( "input#latitude" ).val( point.latLng.lat().toFixed( 10 ) );
                $( "input#longitude" ).val( point.latLng.lng().toFixed( 10 ) );

                getGeocodeData( new google.maps.LatLng( markerPoint.position.lat(), markerPoint.position.lng() ) );

            } );

        }

        function getGeocodeData( latLng )
        {
            geocoder.geocode({'latLng': latLng}, function(results, status) {
                if ( status === google.maps.GeocoderStatus.OK ) {
                    defaults.changePositionMarker( results );//call function to result data geocode
                }
            });        
        }

        function showMyPosition() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(showPosition);
            } else {
                alert("Váš prohlížeč nepodporuje geolokaci");
            }
        }

        function showPosition(position) {
            var location = new google.maps.LatLng( position.coords.latitude, position.coords.longitude );
            changeMarkerPickerLocation( location );
        }
*/
    }( jQuery ) );

} );