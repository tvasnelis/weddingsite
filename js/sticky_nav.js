$(document).ready(function () {

var menu = $('.navbar');
var origOffsetY = menu.offset().top;

function scroll() {
    if ($(window).scrollTop() >= origOffsetY) {
    	$('.navbar').addClass('sticky');
        $('.content').addClass('menu-padding');
    } else {
        $('.navbar').removeClass('sticky');
        $('.content').removeClass('menu-padding');
    }


   }

  document.onscroll = scroll;

$('#myNavbar').on('shown.bs.collapse', function () {
   $("#navbar-toggle").removeClass("glyphicon glyphicon-menu-down").addClass("glyphicon glyphicon-menu-up");
});

$('#myNavbar').on('hidden.bs.collapse', function () {
   $("#navbar-toggle").removeClass("glyphicon glyphicon-menu-up").addClass("glyphicon glyphicon-menu-down");
});


});

