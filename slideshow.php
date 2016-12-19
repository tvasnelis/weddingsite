<html>
	<link rel="stylesheet" href="js/unslider-master/dist/css/unslider.css">
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<body>
		<div class="my-slider">
			<ul>
				<li><img src="images/slideshow/A00.jpg" alt="#";></li>
				<li><img src="images/slideshow/A01.jpg" alt="#";></li>
				<li><img src="images/slideshow/A02.jpg" alt="#";></li>
				<li><img src="images/slideshow/A03.jpg" alt="#";></li>
				<li><img src="images/slideshow/A04.jpg" alt="#";></li>
				<li><img src="images/slideshow/A05.jpg" alt="#";></li>
				<li><img src="images/slideshow/A06.jpg" alt="#";></li>
				<li><img src="images/slideshow/A07.jpg" alt="#";></li>
				<li><img src="images/slideshow/A08.jpg" alt="#";></li>
				<li><img src="images/slideshow/A09.jpg" alt="#";></li>
				<li><img src="images/slideshow/A10.jpg" alt="#";></li>
				<li><img src="images/slideshow/A11.jpg" alt="#";></li>
				<li><img src="images/slideshow/A12.jpg" alt="#";></li>
				<li><img src="images/slideshow/A13.jpg" alt="#";></li>
				<li><img src="images/slideshow/A14.jpg" alt="#";></li>
				<li><img src="images/slideshow/A15.jpg" alt="#";></li>
				<li><img src="images/slideshow/A16.jpg" alt="#";></li>
				<li><img src="images/slideshow/A17.jpg" alt="#";></li>
				<li><img src="images/slideshow/A18.jpg" alt="#";></li>
				<li><img src="images/slideshow/A19.jpg" alt="#";></li>
				<li><img src="images/slideshow/A20.jpg" alt="#";></li>
				<li><img src="images/slideshow/A21.jpg" alt="#";></li>
				<li><img src="images/slideshow/A22.jpg" alt="#";></li>
				<li><img src="images/slideshow/A23.jpg" alt="#";></li>
				<li><img src="images/slideshow/A24.jpg" alt="#";></li>
				<li><img src="images/slideshow/A25.jpg" alt="#";></li>
				<li><img src="images/slideshow/A26.jpg" alt="#";></li>
				<li><img src="images/slideshow/A27.jpg" alt="#";></li>
				<li><img src="images/slideshow/A28.jpg" alt="#";></li>
				<li><img src="images/slideshow/A29.jpg" alt="#";></li>
				<li><img src="images/slideshow/A30.jpg" alt="#";></li>
				<li><img src="images/slideshow/A31.jpg" alt="#";></li>
				<li><img src="images/slideshow/A32.jpg" alt="#";></li>
				<li><img src="images/slideshow/A33.jpg" alt="#";></li>
				<li><img src="images/slideshow/A34.jpg" alt="#";></li>
				<li><img src="images/slideshow/A35.jpg" alt="#";></li>
				<li><img src="images/slideshow/A36.jpg" alt="#";></li>
				<li><img src="images/slideshow/A37.jpg" alt="#";></li>
				<li><img src="images/slideshow/A38.jpg" alt="#";></li>
				<li><img src="images/slideshow/A39.jpg" alt="#";></li>
				<li><img src="images/slideshow/A40.jpg" alt="#";></li>
			</ul>
		</div>
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="js/unslider-master/src/js/unslider.js"></script> 
	<script>
		jQuery(document).ready(function($) {
			$('.my-slider').unslider();
		});
		$('.my-slider').unslider({
			nav: false,
			animation: 'horizontal',
			prev: '<a class="unslider-arrow prev">TEST</a>',
			next: '<a class="unslider-arrow next">TEST</a>',
			// autoplay: true
			// prev: '<a class="unslider-arrow prev"><span class="glyphicon glyphicon-search"></span></a>',
		});

		var scripts = [
			'http://stephband.info/jquery.event.move/js/jquery.event.move.js',
			'http://stephband.info/jquery.event.swipe/js/jquery.event.swipe.js'
		];

		$.getScript(scripts[0]);

		//  Once our script is loaded, we can initSwipe to add swipe support
		$.getScript(scripts[1], function() {
			$('.my-slider').unslider('initSwipe');
		});

	</script>
	<style>
		.unslider {
			margin-bottom: -40px;		
		}
		.my-slider {
			position: absolute;
			height: 400px;
			/*background: lightgray;*/
		}
		.unslider-arrow {
	    position: relative;
	    width: 40px;
	    height: 40px;
	    top: -220px;
	    background: rgba(0,0,0,.2) no-repeat 50% 50%;
	    background-size: 24px 24px;
	    border-radius: 40px;
	    text-indent: -999em;
	    opacity: .6;
	    transition: opacity .2s;
	    cursor: pointer;
		}
		.unslider-arrow.prev {
			background-image: url(images/arrow_left.png);
			float: left;
		} 
		.unslider-arrow.next {
		   background-image: url(images/arrow_right.png);
		   float: right;
		}
		.my-slider img{
			/*height: 400px;*/
			max-width: 100%;
			max-height: 400px;
			padding: 10px 0;
			margin: auto;
			display: block;
    	margin: 0 auto;
		};
		.unslider img {
			display: none;
		}
		.unslider-active img {
			display: block;
		}
	</style>
	</body>
</html>