<?php
require(ROOT_PATH . "inc/guest.php");

// Start Session
session_start();

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
	$_SESSION['errors'] = array();
	$_SESSION['newRsvp'] = true;
} 

?>