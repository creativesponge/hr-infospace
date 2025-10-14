const google_map = {
    init: function() {
        this.maps();
    },
    maps: function() {

        if(typeof google !== 'undefined') {
            let maps = document.getElementsByClassName('map'),
                map_options = {
                    zoom: 10,
                    zoomControl: true,
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.DEFAULT,
                    },
                    mapTypeControl: false,
                    scaleControl: false,
                    scrollwheel: false,
                    streetViewControl: false,
                    overviewMapControl: false,
                    overviewMapControlOptions: {
                        opened: false,
                    },
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    styles: [{
                        "featureType": "administrative",
                        "stylers": [{
                            //"visibility": "off"
                        }]
                    },{
                        "featureType": "poi",
                        "stylers": [{
                            //"visibility": "simplified"
                        }]
                    },{
                        "featureType": "road",
                        "stylers": [{
                            //"visibility": "simplified"
                        }]
                    },{
                        "featureType": "water",
                        "stylers": [{
                            //"visibility": "simplified"
                        }]
                    },{
                        "featureType": "transit",
                        "stylers": [{
                            //"visibility":"simplified"
                        }]
                    },{
                        "featureType": "landscape",
                        "stylers": [{
                            //"visibility": "simplified"
                        }]
                    },{
                        "featureType": "road.highway",
                        "stylers": [{
                            "visibility": "on"
                        }]
                    },{
                        "featureType": "road.local",
                        "stylers": [{
                            "visibility": "on"
                        }]
                    },{
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#FFFFFF"
                        }]
                    },{
                        "featureType": "water",
                        "stylers": [{
                            "color": "#005499"
                        },{
                            "lightness": 52
                        }]
                    },{
                        "stylers": [{
                            "saturation": -77
                        }]
                    },{
                        //"featureType": "road"
                    }],
                };


            let mapMarkerIcon = {
                url: 	'/wp-content/themes/startertheme/dist/assets/images/blocks/map-marker.svg',
                size: 	new google.maps.Size(60, 60),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(30, 30),
                scaledSize: new google.maps.Size(60, 60),
            };

            [].forEach.call(maps, function(map, i) {

                let longitude = map.dataset.longitude,
                    latitude = map.dataset.latitude;

                let mapElem = map,
                    myLatLng = {lat: + latitude, lng: + longitude};

                let mapx = new google.maps.Map(
                    mapElem,
                    map_options
                );

                mapx.setCenter(myLatLng);

                let marker = new google.maps.Marker({
                    map: mapx,
                    draggable: false,
                    animation: google.maps.Animation.DROP,
                    position: myLatLng,
                    icon: mapMarkerIcon
                });
            });
        }
    }
};

export default google_map
