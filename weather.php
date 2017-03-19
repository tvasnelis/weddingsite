<link rel="stylesheet" href="css/weather-icons.min.css">
<link rel="stylesheet" href="css/weather-icons-wind.min.css">

<div id="weather" class="container-fluid text-center sub-font bg-green white-text" href="#travel">
  <div class="justify-content-center">
      <div class="row text-center justify-content-center">
          <?php
          for ($i=0; $i <= 4; $i++) {
            if ($i==0){
              echo "<div class='col col-xs-6 col-xs-offset-3 col-sm-2 col-sm-offset-1'>\n";
            } else {
              echo "<div class='col col-xs-6 col-xs-offset-3 col-sm-2 col-sm-offset-0'>\n";
            }
            echo "<div class='weather-cell bg-yellow'>\n";
            echo "<li><span id='day" . $i . "'> </span></li>\n";
            echo "<canvas id='icon" . $i . "'' class='weather-icon' width='50' height='50'></canvas>\n";
            // echo "<li><span id='desc" . $i . "'></span></li>\n";
            echo "<li><span id='tempMin" . $i . "'></span> <i class='fa fa-long-arrow-right'></i> <span id='tempMax" . $i . "'></span> &degF</li>\n";
            echo "<li><i class='icon glyphicon glyphicon-tint'></i> <span id='humidity" . $i . "'></span>%</li>\n";
            echo "<li><i class='icon wi wi-sunrise'></i> <span id='sunrise" . $i . "'></span></li>\n";
            echo "<li><i class='icon wi wi-sunset'></i> <span id='sunset" . $i . "'></span></li>\n";
            echo "</div>\n";
            echo "</div>\n";
          }
          ?>



            <!-- <li><i id="wind-icon" class="icon wi wi-wind"></i> <span id="wind-speed1"></span> mph</li> -->


          </div>
    </div>
</div>

<script type="text/javascript" src="js/skycons.js"></script>
<script src="js/weather.js" type="text/javascript"></script>
