<?php
require(ROOT_PATH . "inc/guest.php");
require(ROOT_PATH . "inc/functions.php");

// Start Session
sec_session_start();

//Set Session Counter
if( isset( $_SESSION['counter'] ) ) {
	$_SESSION['counter'] += 1;
}else {
	$_SESSION = array();
	$_SESSION['counter'] = 1;
}

// initialize session variables
if ($_SESSION['counter'] == 1) {
	$_SESSION['user'] = new Guest;
	$_SESSION['group'] = array();
	$_SESSION['errors_1'] = array();
	$_SESSION['errors_2'] = array();
	$_SESSION['newRsvp'] = true;
	$_SESSION['temp'] = array();
	$_SESSION['temp'] = array();
} 

?>