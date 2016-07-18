<?php 

$pageTitle = "Tim & Kimberly - Wedding";
$section = "wedding";

include("inc/header.php"); ?>


    <div id="content">
    	<div class="section wedding">
	    	<ul class="gallery_grid sub-font columns-4">
	    		<li>
	    			<img src="images/door_sq.jpg" alt="Rehersal">
	    			<h3>Rehearsal/Welcome Drinks</h3>
	    			<p>Friday</p>
	    			<p>March 24th</p>
	    			</br>
	    			<p>More info coming soon!</p>
	    		</li>
	    		<li>
	    			<img src="images/pharm01.jpg" alt="Pharmacy Museum">
	    			<h3>Ceremony</h3>
	    			<p>Saturday</p>
	    			<p>March 25th</p>
	    			<p>6pm</p>
	    			<p><a href="http://www.pharmacymuseum.org" target="_blank">New Orleans Pharmacy Museum</a></p>
	    		</li>
	    		<li>
	    			<img src="images/napoleon.jpg" alt="Napoleon House">
	    			<h3>Reception</h3>
	    			<p>Saturday</p>
	    			<p>March 25th</p>
	    			<p>8pm</p>
	    			<p><a href="http://www.napoleonhouse.com/" target="_blank">Napoleon House</a></p>
	    		</li>
	    		<li>
	    			<img src="images/door_sq.jpg" alt="Brunch">
	    			<h3>Brunch</h3>
	    			<p>Sunday</p>
	    			<p>March 26th</p>
	    			</br>
	    			<p>More info coming soon!</p>
	    		</li>
    	</div>
    	<section id="frame-map">
            <div id="map"></div>
            <script type="text/javascript" src="js/map.js"></script>
            <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=AIzaSyAoAm89NdoOx95_nHcfWmxVyCkmOnUwcDk" async defer></script>
        </section>
    </div>

</body>
</html>