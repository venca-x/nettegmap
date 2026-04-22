// Global callback for Google Maps JS (async + loading=async). Load API with: callback=netteGMapGoogleApiReady
window.netteGMapGoogleApiReady = function () {
    jQuery(function ($) {

        var map;
        var markers = []; //array of markers
        var infowindow;

        function initializeMap(thisMap, dataMapAttr) {

            infowindow = new google.maps.InfoWindow();

            var mapId = (dataMapAttr.map && typeof dataMapAttr.map.mapId !== 'undefined' && dataMapAttr.map.mapId)
                ? dataMapAttr.map.mapId
                : 'DEMO_MAP_ID';

            var mapProp = {
                mapId: mapId,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: dataMapAttr.map.scrollwheel,
            };

            if (typeof dataMapAttr.map.center !== 'undefined') {
                mapProp.center = new google.maps.LatLng(dataMapAttr.map.center.lng, dataMapAttr.map.center.lat);
            }

            if (typeof dataMapAttr.map.zoom !== 'undefined') {
                mapProp.zoom = dataMapAttr.map.zoom;
            }

            var mapCanvasDiv = thisMap.find("div.nettegmap-canvas");

            //set dimensions of map
            mapCanvasDiv.width(dataMapAttr.map.size.x);
            mapCanvasDiv.height(dataMapAttr.map.size.y);

            map = new google.maps.Map(mapCanvasDiv[0], mapProp);
        }

        $.fn.netteGMapViewer = function () {

            var dataMapAttr = this.data("map-attr");
            if (typeof dataMapAttr !== 'undefined') {

                initializeMap(this, dataMapAttr);

                /////////////////////////////////////////////////////////////////////////
                //viewer - markers
                var bounds = new google.maps.LatLngBounds();

                //view markers
                for (i = 0; i < dataMapAttr.markers.length; i++) {
                    addMarkerToMap(bounds, dataMapAttr.markers[i], i);
                }

                //view polyline
                if (dataMapAttr.hasOwnProperty('polyline') && dataMapAttr.polyline.hasOwnProperty('coordinates')) {

                    var coordinatesArray = [];

                    for (i = 0; i < dataMapAttr.polyline.coordinates.length; i++) {
                        coordinatesArray.push({lat: dataMapAttr.polyline.coordinates[i].latitude, lng: dataMapAttr.polyline.coordinates[i].longitude});
                        ////////
                        var location = new google.maps.LatLng(dataMapAttr.polyline.coordinates[i].latitude, dataMapAttr.polyline.coordinates[i].longitude);
                        bounds.extend(location);
                    }

                    var flightPath = new google.maps.Polyline({
                        path: coordinatesArray,
                        geodesic: true,
                        strokeColor: dataMapAttr.polyline.stroke.color,
                        strokeOpacity: dataMapAttr.polyline.stroke.opacity,
                        strokeWeight: dataMapAttr.polyline.stroke.weight
                    });
                    flightPath.setMap(map);
                }

                if (typeof dataMapAttr.map.center !== 'undefined') {
                    map.setCenter(new google.maps.LatLng(dataMapAttr.map.center.lng, dataMapAttr.map.center.lat));
                } else {
                    //set zoom and center auto - bounds
                    map.fitBounds(bounds);
                }

                if (typeof dataMapAttr.map.zoom !== 'undefined') {
                    //hack set zoom after map.fitBounds
                    var listener = google.maps.event.addListener(map, "idle", function() {
                        map.setZoom(dataMapAttr.map.zoom);
                        google.maps.event.removeListener(listener);
                    });
                }

                //////////////////////////////////////////////////////////////////////////
            }
            return this;
        }

        $.fn.netteGMapLayer = function () {
            var dataMapAttr = this.data("map-attr");

            if (typeof dataMapAttr !== 'undefined') {

                initializeMap(this, dataMapAttr);

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

        $.fn.netteGMapPicker = function (options) {

            var dataMapAttr = this.data("map-attr");

            if (typeof dataMapAttr !== 'undefined') {

                // Set defaults
                defaults = {
                    changePositionMarker: function (results) {
                    }
                };

                // Override defaults with options
                options = $.extend(defaults, options);

                //in search is key ENTER none function
                this.find("#nettegmap-search-box").keydown(function (e) {
                    if (e.keyCode == 13) {
                        //stisknut ENTER, neodesilej formular
                        return false;
                    }
                });

                //search
                var searchBox = new google.maps.places.SearchBox(this.find("#nettegmap-search-box")[0]);

                //search result
                google.maps.event.addListener(searchBox, 'places_changed', function () {

                    var places = searchBox.getPlaces();

                    //set first marker from places
                    if (places.length > 0) {
                        changeMarkerPickerLocation(places[0].geometry.location);
                    }
                });

                initializeMap(this, dataMapAttr);

                //set picker marker
                setMarkerPicker(new google.maps.LatLng($("input#latitude").val(), $("input#longitude").val()));

                this.find("button#my-actual-position").click(function () {
                    showMyPosition();
                });
            }

            return this;
        }


        var pickerOptions = (typeof window.netteGMapPickerOptions === 'object' && window.netteGMapPickerOptions !== null)
            ? window.netteGMapPickerOptions
            : {};

        $("div.nettegmap-viewer").netteGMapViewer();
        $("div.nettegmap-layer").netteGMapLayer();
        $("div.nettegmap-picker").netteGMapPicker(pickerOptions);


        ////////////////////////////////////////////////////////////////////////////////////////////////////
        $.fn.setMarkerPosition = function (lon, lat) {
            var location = new google.maps.LatLng(lat, lon);
            changeMarkerPickerLocation(location);
        };
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        function addMarkerToMap(bounds, marker, i) {

            var location = new google.maps.LatLng(marker.latitude, marker.longitude);

            var markerOptions = {
                position: location,
                map: map,
                title: typeof marker.title !== 'undefined' ? String(marker.title) : '',
                gmpClickable: true
            };

            if (typeof marker.icon !== 'undefined') {
                var iconImg = document.createElement('img');
                iconImg.src = marker.icon;
                iconImg.style.maxHeight = '40px';
                markerOptions.content = iconImg;
            }

            var markerPoint = new google.maps.marker.AdvancedMarkerElement(markerOptions);

            markerPoint.addListener('click', (function (markerPoint, i) {
                return function () {
                    var content = "";
                    if (marker.title != "") {
                        content = content + "<strong>" + marker.title + "</strong><br/>";
                    }
                    content = content + "" + marker.description;
                    infowindow.setContent(content);
                    infowindow.open({map: map, anchor: markerPoint});
                };
            })(markerPoint, i));

            bounds.extend(location);

            markers.push(markerPoint);

        }

        /**
         * Set position of marker picker
         * @param location
         */
        function setMarkerPicker(location) {
            var markerPoint = new google.maps.marker.AdvancedMarkerElement({
                position: location,
                map: map,
                gmpDraggable: true
            });

            map.setCenter(location);
            markers.push(markerPoint);

            google.maps.event.addListener(markerPoint, 'dragend', function () {
                var pos = markerPoint.position;
                if (!pos) {
                    return;
                }
                var lat = typeof pos.lat === 'function' ? pos.lat() : pos.lat;
                var lng = typeof pos.lng === 'function' ? pos.lng() : pos.lng;
                $("input#latitude").val(Number(lat).toFixed(10));
                $("input#longitude").val(Number(lng).toFixed(10));
                getGeocodeData(
                    new google.maps.LatLng(
                        typeof pos.lat === 'function' ? pos.lat() : pos.lat,
                        typeof pos.lng === 'function' ? pos.lng() : pos.lng
                    )
                );
            });

            changeMarkerPickerLocation(location);
        }

        /**
         * Calbback after change marker picker position
         * @param location
         */
        function changeMarkerPickerLocation(location) {
            markers[0].position = location;
            map.setCenter(location);

            var pos = markers[0].position;
            if (pos) {
                var lat = typeof pos.lat === 'function' ? pos.lat() : pos.lat;
                var lng = typeof pos.lng === 'function' ? pos.lng() : pos.lng;
                $("input#latitude").val(lat);
                $("input#longitude").val(lng);
            }
        }

        function getGeocodeData(latLng) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({location: latLng}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    defaults.changePositionMarker(results);
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
            var location = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            changeMarkerPickerLocation(location);
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

        MapOverlay.prototype.draw = function () {
            var overlayProjection = this.getProjection();
            var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());//spodni leva
            var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());//horni prava

            var div = this.div_;
            div.style.left = sw.x + 'px';
            div.style.top = ne.y + 'px';
            div.style.width = (ne.x - sw.x) + 'px';
            div.style.height = (sw.y - ne.y) + 'px';
        };

        MapOverlay.prototype.onRemove = function () {
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        };

        MapOverlay.prototype.onAdd = function () {
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

    });

};
