var forecastDt = [];
var forecastTemp = [];
var forecastPrecip = [];

$(document).ready(function() {

  // find location
  var loc = {
    lat: 29.9511,
    lon: -90.0715
  };
    // request current weather data from Dark Sky
  $.ajax({
    async: true, //thats the trick
    url: "https://api.darksky.net/forecast/0f208f1652fa0e8ce237e1dbe96e6596/" + loc.lat + "," + loc.lon + "?exclude=currently,minutely,hourly,alerts,flags",
    dataType: "jsonp",
    type: "GET",
    success: function(wData){
      console.log(wData);
      // add current weather data to page
      for (var i=0; i<=4; i++) {
        document.getElementById("day" + i).innerHTML = stringifyDay(wData.daily.data[i].time);
        var skycons = new Skycons({"color": "#fff"});
        skycons.add("icon" + i, wData.daily.data[i].icon);
        skycons.play();
        // document.getElementById("desc" + i).innerHTML = wData.daily.data[i].summary;
        document.getElementById("tempMin" + i).innerHTML = Math.round(wData.daily.data[i].temperatureMin);
        document.getElementById("tempMax" + i).innerHTML = Math.round(wData.daily.data[i].temperatureMax);
        document.getElementById("humidity" + i).innerHTML = Math.round(wData.daily.data[i].humidity);
        document.getElementById("sunrise" + i).innerHTML = stringifyTime(wData.daily.data[i].sunriseTime);
        document.getElementById("sunset" + i).innerHTML = stringifyTime(wData.daily.data[i].sunsetTime);
      }
    }
  });
})





// outputs day as string from given datetime
// Day, Month, Date HH:MMPM
function stringifyDay(dt) {
  if (dt !== undefined) {
    var datetime = new Date(dt * 1000);
  } else {
    var datetime = new Date();
  }
  var day = dayFromIndex(datetime.getDay());
  return day;
}

// converts datetime to formatted string
// HH:MMPM
function stringifyTime (dt) {
  if (dt !== undefined) {
    var datetime = new Date(dt*1000);
  } else {
    var datetime = new Date();
  }
  var hours = datetime.getHours();
  var minutes = "0" + datetime.getMinutes();

  var suffix = hours >= 12 ? "PM":"AM";
  hours = ((hours + 11) % 12 + 1);

  var minutes = "0" + datetime.getMinutes();
  return hours + ':' + minutes.substr(-2) + suffix;
}

/*
returns month name given the index (0-index)
*/
function monthFromIndex(index) {
  var monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
  ];
  return monthNames[index];
}

/*
returns day name given the index (0-index)
*/
function dayFromIndex(index) {
  var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday",
  "Saturday"];
  return dayNames[index];
}

// calculates and returns compass direction based on degrees from true North
function degToCompass(deg) {
    var dir = "";
    var val = Math.trunc((deg/22.5)+.5);
    var arr=["N","NNE","NE","ENE","E","ESE", "SE", "SSE","S","SSW","SW","WSW","W","WNW","NW","NNW"]
    return arr[(val % 16)]
}
