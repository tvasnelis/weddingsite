<?php

$status = "LIVE";

	
	if ($status == "LOCAL") {
		define("BASE_URL","/timandkimberly");
		define("ROOT_PATH",$_SERVER["DOCUMENT_ROOT"] . "/timandkimberly/");  // local ROOT PATH
		define('DB_HOST', 'timandkimberly.com');  // local HOST
	} else {
		define("BASE_URL","/");
		define("ROOT_PATH",$_SERVER["DOCUMENT_ROOT"] . "/");  // live ROOT PATH
		define('DB_HOST', 'localhost');  // live HOST
	}
	
	// Define MySQL database settings
	define('DB_NAME', 'timvasne_wedding');    		// The name of the database
	define('DB_USER', 'timvasne_user');     		// MySQL username
	define('DB_PASSWORD', 'KQSIJGNu59vT'); 	// MySqlpassword
	
	define('DB_PORT', '3306');
