$( document ).ready( function() {

    ( function( $ ) {

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

    }( jQuery ) );

} );