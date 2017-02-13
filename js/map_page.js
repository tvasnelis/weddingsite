// Note: This script requires that you consent to location sharing when
// prompted by your browser. If you see the error "The Geolocation service
// failed.", it means you probably did not give permission for the browser to
// locate you.

var customLabel = {
      restaurant: {
        label: 'R'
      },
      bar: {
        label: 'B'
      }
    };

function initMap() {
    var mapOptions = {
        zoom: 16,
        center: new google.maps.LatLng(29.954914,-90.065112), 
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoomControl: true,
        disableDoubleClickZoom: false,
        mapTypeControl: false,
        scaleControl: true,
        scrollwheel: true,
        draggable : true,
        scaleControl: false
    };
    var mapDiv = document.getElementById('map_page');        
    var map = new google.maps.Map(mapDiv, mapOptions);
    var infoWindow = new google.maps.InfoWindow;

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

    // Change this depending on the name of your PHP or XML file
    downloadUrl('markers_generateXML.php', function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName('marker');
        Array.prototype.forEach.call(markers, function(markerElem) {
            var name = markerElem.getAttribute('name');
            var address = markerElem.getAttribute('address');
            var type = markerElem.getAttribute('type');
            var point = new google.maps.LatLng(
                parseFloat(markerElem.getAttribute('lat')),
                parseFloat(markerElem.getAttribute('lng')));

            var infowincontent = document.createElement('div');
            var strong = document.createElement('strong');
            strong.textContent = name
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text');
            text.textContent = address
            infowincontent.appendChild(text);
            var icon = customLabel[type] || {};
            var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
            });
            marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
            });
        });
    });




    function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
            if (request.readyState == 4) {
                request.onreadystatechange = doNothing;
                callback(request, request.status);
            }
        };  

        request.open('GET', url, true);
        request.send(null);
    }

    function doNothing() {}


        

    function bindInfoWindow(marker, map, infowindow, html) { 
        google.maps.event.addListener(marker, 'click', function() { 
            infowindow.setContent(html); 
            infowindow.open(map, marker); 
        }); 
    } 
}



