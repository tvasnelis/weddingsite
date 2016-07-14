var map_locations = [{
    "name": "New Orleans Pharmacy Museum",
    "description": "Ceremony Venue",
    "position": {
            "lat": 29.956051,
            "lng": -90.064935
    }
}, {
    "name": "Napolean House",
    "description": "Reception Venue",
    "position": {
            "lat": 29.955879,
            "lng": -90.065057
    }
}, {
    "name": "Cafe Amelie",
    "description": "Rehersal Dinner Venue",
    "position": {
            "lat": 29.959763,
            "lng": -90.062985
    }
}]

var markers = [];
var map;


function initMap() {
                    var mapOptions = {
                    zoom: 17,
                    center: new google.maps.LatLng(29.955714,-90.065112), 
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoomControl: true,
                    disableDoubleClickZoom: false,
                    mapTypeControl: false,
                    scaleControl: true,
                    scrollwheel: false,
                    draggable : true,
                    scaleControl: false
                    };
                
                    var mapDiv = document.getElementById('map');        
                    var map = new google.maps.Map(mapDiv, mapOptions);

                    // maintain center on window resize
                    google.maps.event.addDomListener(window, "resize", function() {
                        var center = map.getCenter();
                        google.maps.event.trigger(map, "resize");
                        map.setCenter(center); 
                    });

                    //set styles
                    map.set('styles', [
                        {
                            stylers: [
                                { visibility: 'off' },
                            ]
                        }, {
                            featureType: 'road',
                            elementType: 'geometry',
                            stylers: [
                                { visibility: 'on' },
                                { color: '#93c2b2' },
                                { weight: 1.5 },
                                { saturation: -10 },
                            ]
                        }, {
                            featureType: 'road',
                            elementType: 'labels',
                            stylers: [
                                { visibility: 'on' },
                                { saturation: -50 },
                                { invert_lightness: false }
                            ]
                        }, {
                            featureType: 'landscape',
                            elementType: 'geometry',
                            stylers: [
                                { visibility: 'on' },
                                { hue: '#d1c8b7' },
                                { gamma: 1.5 },
                                { saturation: -25 }
                            ]
                        }, {
                            featureType: 'water',
                            elementType: 'geometry',
                            stylers: [
                                { visibility: 'on' },
                                { hue: '#bbc9e4' },
                                { saturation: -50 }
                            ]
                        }, {
                            featureType: 'water',
                            elementType: 'labels',
                            stylers: [
                                { visibility: 'on' },
                                { saturation: -50 }
                            ]
                        }, {
                            featureType: 'poi',
                            elementType: 'geometry',
                            stylers: [
                                { visibility: 'on' }
                            ]
                        }
                    ]);

                    function drop() {
                      clearMarkers();
                      for (var i = 0; i < map_locations.length; i++) {
                        addMarkerWithTimeout(i);
                      }
                    }

                    function addMarkerWithTimeout(index) {
                      window.setTimeout(function() {
                        markers.push(new google.maps.Marker({
                          position: map_locations[index].position,
                          title: map_locations[index].name,
                          map: map,
                          animation: google.maps.Animation.DROP
                        }));
                      }, index*300);
                    }

                    function clearMarkers() {
                      for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                      }
                      markers = [];
                    }

                    drop();

                } 


