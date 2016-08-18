<?php

// Start Session
//SessionManager::sessionStart('InstallationName');
session_start();

//Set Session Counter
if(isset($_SESSION['counter'])) {
	$_SESSION['counter'] += 1;
}else {
	$_SESSION = array();
	$_SESSION['counter'] = 1;
}

// initialize session variables
if ($_SESSION['counter'] == 1) {
	$_SESSION['guests'] = array();
} 

?>