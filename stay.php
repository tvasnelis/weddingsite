<?php 

$pageTitle = "Tim & Kimberly - Stay";
$section = "stay";

include("inc/header.php"); ?>

    <div id="content">
    	<div class="section wedding">
	    	<ul class="gallery_grid sub-font columns-4">
	    		<li>
	    			<img src="images/montelone.jpg" alt="Hotel Monteleone">
	    			<h3><a href="http://www.hotelmonteleone.com/" target="_blank">Hotel Monteleone</a></h3>
	    			<p>214 Royal St.</p>
	    			<p>The wedding party's homebase</p>
	    			<!--<p>The wedding party's homebase. A historic landmark opened in 1886 and home to the Carousel Piano Bar and Lounge.</p> -->
	    			<!--<p>(Reference 'Bean & Vasnelis Wedding')</p> -->
	    		</li>
	    		<li>
	    			<img src="images/bienville.jpg" alt="Bienville House">
	    			<h3><a href="http://bienvillehouse.com/" target="_blank">Bienville House</a></h3>
	    			<p>320 Decatur St.</p>

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