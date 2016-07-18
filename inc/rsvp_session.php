<?php

// Start Session
session_start();

if( isset( $_SESSION['counter'] ) ) {
	$_SESSION['counter'] += 1;
	}else {
		$_SESSION['counter'] = 1;
}

// initialize session variables
if ($_SESSION['counter'] == 1) {
	$_SESSION['user'] = array(
		'GuestId' => "",
		'FirstName' => "",
		'LastName' => "",
		'GroupId' => "",
		'Attending' => "",
		'Email' => "",
		'PlusOne' => "0"
	);
	$_SESSION['guest'] = array(
		'GuestId' => "",
		'FirstName' => "",
		'LastName' => "",
		'GroupId' => "",
		'Attending' => "",
		'Email' => "",
		'PlusOne' => "0"
	);
	$_SESSION['errors'] = array();
	$_SESSION['newRsvp'] = true;;
} 

// Define MySQL database settings
define('DB_NAME', 'timvasne_wedding1');    		// The name of the database
define('DB_USER', 'timvasne_tim');     		// MySQL username
define('DB_PASSWORD', 'TjV.04*23'); 	// MySqlpassword
define('DB_HOST', 'timandkimberly.com');
define('DB_PORT', '3306');

?>