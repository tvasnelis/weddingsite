<?php
require_once("inc/config.php");
include("inc/header.php");

?>
<script type="text/javascript" src="js/downloadxml.js"></script>


		<div id="map_page_wrap" class="container-fluid sub-font">
			<div class="row">


				<div id="side_bar" class="col-sm-4 col-md-2">

					<ul class="goo-collapsible">
				    <li class="dropdown"><a class="" href="#">Restaurants</a>
				        <ul id="ul-restaurant">
				        </ul>
				    </li>
				    <li class="dropdown"><a href="#">Bars</a>
				        <ul id="ul-bar">
				        </ul>
				    </li>
				    <li class="dropdown"><a href="#">Entertainment</a>
				        <ul id="ul-entertain">
				        </ul>
				    </li>
				    <li class="dropdown"><a href="#">Hotels</a>
				        <ul id="ul-hotel">

				        </ul>
				    </li>
				    <li class="dropdown"><a href="#">Venues</a>
				        <ul id="ul-venue">
				        </ul>
				    </li>
				    <li class="dropdown"><a href="#">Tourist</a>
				        <ul id="ul-tourist">
				        </ul>
				    </li> 
				    <li class="dropdown"><a href="#">Beer</a>
				        <ul id="ul-beer">
				        </ul>
				    </li>             
					</ul>
				</div>


				<div id="map_page" class="col-sm-8 col-md-10">
				  <script type="text/javascript" src="js/map_page.js"></script>
				  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=AIzaSyAoAm89NdoOx95_nHcfWmxVyCkmOnUwcDk" async defer></script>
				</div>
				
			</div>
		</div>

		<!-- <div id="map-detail">
			<p>TEST</p>
		</div> -->


<?php include("footer.php"); ?>


<style type="text/css">
		.map-detail { display: none; }
		.detail-item { display: none; }
</style>

<script>

	$(".goo-collapsible > li > a").on("click", function(e){
	    //if submenu is hidden, does not have active class 
	    if(!$(this).hasClass("active")) {
	         
	        // hide any open menus and remove active classes
	        $(".goo-collapsible li ul").slideUp(350);
	        $(".goo-collapsible li a").removeClass("active");
	       
	        // open submenu and add the active class
	        $(this).next("ul").slideDown(350);
	        $(this).addClass("active");
	    //if submenu is visible   
	    }else if($(this).hasClass("active")) {
	        //hide submenu and remove active class
	        $(this).removeClass("active");
	        $(this).next("ul").slideUp(350);
	    }
	});

</script>



