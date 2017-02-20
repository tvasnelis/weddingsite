<?php
include("inc/header.php");
?>
<script type="text/javascript" src="js/downloadxml.js"></script>


		<div id="map_page_wrap" class="container-fluid text-center sub-font">
			<div class="row">
				<div id="side_bar" class="col-sm-4 col-md-2">
				</div>
				<div id="map_page" class="col-sm-8 col-md-10">
				  <script type="text/javascript" src="js/map_page.js"></script>
				  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=AIzaSyAoAm89NdoOx95_nHcfWmxVyCkmOnUwcDk" async defer></script>
				</div>
			</div>
		</div>
	</body>
</html>

<?php include("footer.php"); ?>

<style type="text/css">
		.map-detail { display: none; }
</style>
