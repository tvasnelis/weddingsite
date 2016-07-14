<?php 

// Start Session
session_start();

// ** MySQL database settings
define('DB_NAME', 'timvasne_wedding1');    		// The name of the database
define('DB_USER', 'timvasne_tim');     		// MySQL username
define('DB_PASSWORD', 'TjV.04*23'); 	// MySqlpassword
define('DB_HOST', 'timandkimberly.com');
define('DB_PORT', '3306');


// initialize validation error variables
$error_db = false;
$error_firstname = "";
$error_lastname = "";
$error_email = "";
$error_guestname = "";

// initialize session variables
if (!isset($_GET["status"])) {
	//session_destroy();
	$_SESSION['firstname'] = "";
	$_SESSION['lastname'] = "";
	$_SESSION['email_user'] = "";
	$_SESSION['result'] = array();
}

// read POST form data for SUBMIT 1 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit1'])) {

	// validate form data and read values
	if (empty($_POST["firstname"])) {
        $error_firstname = "Missing";
    } else {
    	$firstname = trim(filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING));
    	$firstname = ucwords(strtolower($firstname));
    }

    if (empty($_POST["lastname"])) {
        $errorlastname = "Missing";
    } else {
    	$lastname = trim(filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING));
    	$lastname = ucwords(strtolower($lastname));
    }

    if (empty($_POST["email"]))  {
        $error_email = "Missing";
    }
    else {
        $email_user = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));;
    }

	if ($_POST["address"] != "") {
		echo "Bad form input";
		exit;
	}

	// connect to database
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

	// query database for user name (first and last)
	try {
		$results = $db->prepare("SELECT GuestId, FirstName, LastName, Email, GroupId, Attending FROM Guests WHERE GroupId = (SELECT GroupID FROM Guests WHERE FirstName = :firstname  AND LastName = :lastname)");
		$results->bindValue(':firstname', $firstname);
		$results->bindValue(':lastname', $lastname);
		$results->execute();
	} catch (Exception $e) {
		echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
		exit;
	}

	// set session variables
	$_SESSION['results'] = $results->fetchAll(PDO::FETCH_ASSOC);
	$_SESSION['firstname'] = $firstname;
	$_SESSION['lastname'] = $lastname;
	$_SESSION['email_user'] = $email_user;

	if (empty($_SESSION['results'])) {
	    header("location:rsvp.php?status=invite_notfound");
	    exit;
	}

	if (!empty($_SESSION['results'])) {
	    header("location:rsvp.php?status=invite_found");
	    exit;
	}
}

// read POST form data for SUBMIT 2 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2'])) {

	// connect to database
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
																																															
	//update rsvp
	foreach ($_SESSION['results'] as $guest) {
		$id = $guest["GuestId"];
		if (array_key_exists($id, $_POST)) {
			$sql = "UPDATE Guests SET Attending=" . $_POST[$id] . " WHERE GuestId=" . $id ;
			try {
					$stmt = $db->prepare($sql);
					$stmt->execute();
				} catch (Exception $e) {
					$pageTitle = "Tim & Kimberly - RSVP";
					$section = "rsvp";
					include("inc/header.php");
					echo "<link rel='stylesheet type='text/css href='css/rsvp.css'>";
					echo "<p class='error sub-font'>Error writing to database. Please try again later.</p>";
					exit;
				}
		}
	}

	header("location:rsvp.php?status=thanks");
	exit;
	
}


$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

include("inc/header.php");
?>

<link rel="stylesheet" type="text/css" href="css/rsvp.css">

    <div id="wrap" class="sub-font">
	<?php if ($error_firstname != "" OR $error_lastname != "" OR $error_email != "") {
		echo "<p class='error sub-font'>All fields are requried.</p>";
	}
	?>

	<?php if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
        		echo "<p>Thank You!</p>";
    } else { ?>

    <div id='form_wrap' <?php if ($error_firstname != "" OR $error_lastname != "" OR $error_email != "" OR (isset($_GET["status"]))) { echo "class='state1'"; } ?>>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="rsvp_form">
        	
			<?php if (isset($_GET["status"]) && $_GET["status"] == "invite_notfound") {
				//echo "<p>Sorry, your invitation was not found.  Please try again.</p>";
				//echo "<p>Enter your name exactly as it appears on your invitation.</p>";
				echo "<p>RSVP function coming soon!</p>";
				echo "<style>";
        		echo "#step1 {display: block}";
        		echo "#step2 {display: none}";
        		echo "</style>";
			} ?>

        	<?php if (!isset($_GET["status"])) {
        		echo "<style>";
        		echo "#step1 {display: block}";
        		echo "#step2 {display: none}";
        		echo "</style>";
        	} ?>
			<div id="step1">
	           	<label for="firstname" class="step1">First Name: </label>
	            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($_SESSION['firstname']);?>" />
	            <label for="lastname">Last Name: </label>
	            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($_SESSION['lastname']);?>" />
	            <label for="email">Email Address: </label>
	            <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($_SESSION['email_user']);?>" />
	            <label style="display:none" for="address">Address</label>
				<textarea style="display:none" id="address" name="address"></textarea>
				<p  style="display:none">Please leave this field blank.</p>
	            <input type="submit" name ="submit1" value="Find Invitation" />
	         </div>

	        <?php if (isset($_GET["status"]) && $_GET["status"] == "invite_found") {
        		echo "<style>";
        		echo "#step1 {display: none}";
        		echo "#step2 {display: block}";
        		echo "</style>";
        	} ?>

        	<div id="step2">
				<p class='sub-font'>Your invitation was found!</p>
				<p class='sub-font'>Please mark the check box for all guests that are attending.</p>
				<?php 
				// display check box for each guest and set up array for result
				$i = 1;
				foreach ($_SESSION['results'] as $guest) {
					$id = $guest["GuestId"];
					$fname = $guest['FirstName'];
					$lname = $guest['LastName'];
					$att = 	$guest['Attending'];
				?>

					<input id="<?php echo $id . 'hidden' ?>" type="hidden" value="0" name="<?php echo $id ?>">
					<input id="<?php echo $id ?>" type="checkbox" value="1" name="<?php echo $id ?>" <?php if ($att==1) {echo " checked='checked'";} ?>>
					<label for="guests[]" ><?php echo $fname . " " . $lname ?></label><br>

	        	<?php } ?>
	        	<input type="submit" name ="submit2" value="Submit" />
	        </div>

	<?php } ?>

        </form>
		</div>
    </div>

</body>
</html>

