var map;

function initMap() {

    var customIcons = {
        restaurant: {
            icon: 'http://www.googlemapsmarkers.com/v1/93c2b2/'
        },
        bar: {
            icon: 'http://www.googlemapsmarkers.com/v1/bbc9e4/'
        },
        other: {
            icon: 'http://www.googlemapsmarkers.com/v1/9b9b9b/'
        },
        venue: {
            icon: 'http://www.googlemapsmarkers.com/v1/d8d2a5/'
        },
        hotel: {
            icon: 'http://www.googlemapsmarkers.com/v1/51232d/'
        }
    };

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

    map = new google.maps.Map(document.getElementById('map_page'), mapOptions);
    var infoWindow = new google.maps.InfoWindow;

    // Create the DIV to hold the control and call the constructor passing in this DIV
    var geolocationDiv = document.createElement('div');
    var geolocationControl = new GeolocationControl(geolocationDiv, map);

    map.controls[google.maps.ControlPosition.TOP_CENTER].push(geolocationDiv);

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
            var icon = customIcons[type] || {};
            var marker = new google.maps.Marker({
                map: map,
                position: point,
                animation: google.maps.Animation.DROP,
                icon: icon.icon
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
}

function GeolocationControl(controlDiv, map) {

    // Set CSS for the control button
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#93c2b2';
    controlUI.style.borderStyle = 'solid';
    controlUI.style.borderWidth = '1px';
    controlUI.style.borderColor = '#FFF';
    controlUI.style.height = '28px';
    controlUI.style.marginTop = '5px';
    controlUI.style.cursor = 'pointer';
    controlUI.style.textAlign = 'center';
    controlUI.title = 'button';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control text
    var controlText = document.createElement('div');
    controlText.style.fontFamily = 'Arial,sans-serif';
    controlText.style.fontSize = '10px';
    controlText.style.color = 'white';
    controlText.style.paddingLeft = '10px';
    controlText.style.paddingRight = '10px';
    controlText.style.marginTop = '8px';
    controlText.innerHTML = 'Find My Location';
    controlUI.appendChild(controlText);

    // Setup the click event listeners to geolocate user
    google.maps.event.addDomListener(controlUI, 'click', geolocate);
}

function geolocate() {

    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function (position) {

            var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

            // Create a marker and center map on user location
            // marker = new google.maps.Marker({
            //     position: pos,
            //     draggable: true,
            //     animation: google.maps.Animation.DROP,
            //     map: map,
                
            // });

            map.setCenter(pos);
        });
    }
}
