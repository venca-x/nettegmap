$(function() {

    var infowindow = new google.maps.InfoWindow();
    
    //exist NetteGMapPicker?
    if ($('#nette-g-map').length === 1) {

        //get data atribut
        var dataAttr = $('#nette-g-map').data("nette-g-map-picker");

        var map = new google.maps.Map($('#nette-g-map-canvas')[0], {
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoom: dataAttr.zoom,
            scrollwheel: false
        });

        //set dimensions of map
        $("#nette-g-map-canvas").width(dataAttr.size.x);
        $("#nette-g-map-canvas").height(dataAttr.size.y);
        //////////////////////////////////

        var markers = []; //array markers

        if ($('#nette-g-map #nette-g-map-search-box').length === 1)
        {
            //picker

            var searchBox = new google.maps.places.SearchBox($("#nette-g-map-search-box")[0]);

            //search result
            google.maps.event.addListener(searchBox, 'places_changed', function() {

                var places = searchBox.getPlaces();

                //clear all markers
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }

                //set first marker from places
                if (places.length > 0) {
                    setMarkerPicker(places[0].geometry.location);
                }
            });

            //set default marker
            setMarkerPicker(new google.maps.LatLng($("input#latitude").val(), $("input#longitude").val()));
        }
        else
        {
            //viewer
            var bounds = new google.maps.LatLngBounds();
            //view markers
            for (i = 0; i < dataAttr.markers.length; i++)
            {
                setMarkerViewer(dataAttr.markers[i], i);
            }

            if (typeof dataAttr.zoom === 'undefined') {
                //set zoom and center auto - bounds
                map.fitBounds(bounds);                
            }
            else
            {
                //is defined zoom, set center map
                //set center from first marker
                map.setCenter( new google.maps.LatLng(dataAttr.markers[0].latitude, dataAttr.markers[0].longitude) );
            }
        }

        function setMarkerPicker(location) {
            markerPoint = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true
            });

            map.setCenter(location);

            //set position marker to inputs
            $("input#latitude").val(markerPoint.position.lb);
            $("input#longitude").val(markerPoint.position.mb);

            google.maps.event.addListener(markerPoint, 'dragend', function(point) {
                $("input#latitude").val(point.latLng.lat().toFixed(10));
                $("input#longitude").val(point.latLng.lng().toFixed(10));
            });

            markers.push(markerPoint);
        }


        function setMarkerViewer(marker, i) {

            var location = new google.maps.LatLng(marker.latitude, marker.longitude);

            //create marker
            markerPoint = new google.maps.Marker({
                position: location,
                map: map,
                draggable: false,
                title: marker.title
            });
            
            //set icon whenn is set
            if (typeof marker.icon !== 'undefined') {
                markerPoint.setIcon(marker.icon);
            }

            google.maps.event.addListener(markerPoint, 'click', (function(markerPoint, i) {
                return function() {
                    infowindow.setContent(marker.description);
                    infowindow.open(map, markerPoint);
                };
            })(markerPoint, i));

            bounds.extend(location);

            //add marker to array
            markers.push(markerPoint);
        }

    }

});

