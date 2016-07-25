<?php
// connect to guestlist database
	try {
		$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT,"timvasne_tim","TjV.04*23");
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$db->exec("SET NAMES 'utf8'");
	} catch (Exception $e) {
		$pageTitle = "Tim & Kimberly - RSVP";
		$section = "rsvp";
		include("inc/header.php");
		echo "<link rel='stylesheet type='text/css href='css/rsvp.css'>";
		echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
		exit;
	}
