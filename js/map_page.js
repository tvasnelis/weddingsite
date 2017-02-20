// this variable will collect the html which will eventually be placed in the side_bar
var side_bar_html = "";

// arrays to hold copies of the markers and html used by the side_bar
// because the function closure trick doesnt work there
var gmarkers = [];

// global "map" variable
var map = null;

// variable to store custom icons
var customIcons = {
    restaurant: {
        icon: 'http://www.googlemapsmarkers.com/v1/d8d2a5/'
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
    var infowindow = new google.maps.InfoWindow;

    google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
    });

    // Read the data from xml
    downloadUrl('markers_generateXML.php', function(doc) {
        var xmlDoc = xmlParse(doc);
        var markers = xmlDoc.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
            // obtain the attribues of each marker
            var name = markers[i].getAttribute('name');
            var address = markers[i].getAttribute('address');
            var type = markers[i].getAttribute('type');
            var point = new google.maps.LatLng(
            parseFloat(markers[i].getAttribute('lat')),
            parseFloat(markers[i].getAttribute('lng')));
            var html = markers[i].getAttribute('html');
            var label = markers[i].getAttribute('name');
            // create the marker
            var icon = customIcons[type] || {};
            var marker = new google.maps.Marker({
                position: point,
                map: map,
                animation: google.maps.Animation.DROP,
                icon: icon.icon,
                zIndex: Math.round(point.lat()*-100000)<<5
            });
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        var name = markers[i].getAttribute("name");
                        var address = markers[i].getAttribute("address");
                        var desc = markers[i].getAttribute("html");
                        var website = markers[i].getAttribute("website");
                        var img = markers[i].getAttribute("img");
                        var html = "<b>" + name + "</b> <br/>" + address + "</b> <br/>" + desc +"</b> <br/><a href='" + website + "' target='_blank'>" + website + "</a>";
                        var html = html + "<img src=/images/map/'" + img + "'";
                        infowindow.setContent(html);
                        infowindow.open(map, marker, html);
                        map.setCenter(this.getPosition())
                    }
                })(marker, i));
            // save the info we need to use later for the side_bar
            gmarkers.push(marker);
            // add details to map detail
            document.getElementById('map-detail').innerHTML += '<div id="detail-' + name + '" class="detail-item" style="display: none"> <p>' + name + '</p> </div>';
            // add a line to the side_bar html
            side_bar_html += '<a href="#" onclick="javascript:display_detail(' + (gmarkers.length-1) + ', detail-' + name + ')">' + name + '<\/a><br>'

        }
        // put the assembled side_bar_html contents into the side_bar div
        document.getElementById("side_bar").innerHTML = side_bar_html;
        // hide all details
        //document.getElementsByClassName("detail-item").style.display = "none";
    });

    // Create the DIV to hold the control and call the constructor passing in this DIV
    var geolocationDiv = document.createElement('div');
    var geolocationControl = new GeolocationControl(geolocationDiv, map);

    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(geolocationDiv);

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


}

// This function picks up the click and opens the corresponding info window
function myclick(i) {
  google.maps.event.trigger(gmarkers[i], "click");
}

function HideContent(d) {
    document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
    document.getElementById(d).style.display = "block";
}
function ReverseDisplay(d) {
    if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
    else { document.getElementById(d).style.display = "none"; }
}

function display_detail(i, d) {
    myclick(i);
    ShowContent(d);
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


