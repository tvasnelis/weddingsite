<?php 

include("inc/email_rsvp.php");
include("inc/rsvp_session.php");



// copy session variables to local working variables
$user = array(
	'GuestId' => $_SESSION['user']['GuestId'],
	'FirstName' => $_SESSION['user']['FirstName'],
	'LastName' => $_SESSION['user']['LastName'],
	'GroupId' => $_SESSION['user']['GroupId'],
	'Attending' => $_SESSION['user']['Attending'],
	'Email' => $_SESSION['user']['Email']
);
$guest = array(
	'GuestId' => $_SESSION['guest']['GuestId'],
	'FirstName' => $_SESSION['guest']['FirstName'],
	'LastName' => $_SESSION['guest']['LastName'],
	'GroupId' => $_SESSION['guest']['GroupId'],
	'Attending' => $_SESSION['guest']['Attending'],
	'Email' => $_SESSION['guest']['Email']
);

$errors = $_SESSION['errors'];

// read POST form data for form 1
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	// validate form data and read values
	if (empty($_POST["firstname"])) {
        $error = true;
        $errors["firstname"] = true;
    } else {
    	$user['FirstName'] = trim(filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING));
    	$user['FirstName'] = ucwords(strtolower($user['FirstName']));
    	unset($errors["firstname"]);
    }

    if (empty($_POST["lastname"])) {
        $error = true;
        $errors["lastname"] = true;
    } else {
    	$user['LastName'] = trim(filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING));
    	$user['LastName'] = ucwords(strtolower($user['LastName']));
    	unset($errors["lastname"]);
    }

    // check for input on hidden form field
	if ($_POST["address"] != "") {
		echo "Bad form input.";
		exit;
	}

	//Save form data to session variables
	$_SESSION['user']['FirstName'] = $user['FirstName'];
	$_SESSION['user']['LastName'] = $user['LastName'];
	$_SESSION['errors'] = $errors;

	// if no validation error query database
	if (empty($errors)) {
		
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

		// query database for on first and last name return GuestId
		try {
			
			$user_query = $db->prepare("SELECT GuestId, FirstName, LastName, GroupId, Attending, Email FROM Guests WHERE FirstName = :FirstName AND LastName = :LastName");
			foreach ($user as $param => $value) {
				if (preg_match ('/.*Name$/', $param)) {
					$user_query->bindValue(":".$param,$value,PDO::PARAM_STR);
				}
			}
			$user_query->execute();
		} catch (Exception $e) {
			echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
			exit;
		} 
		$user_db = $user_query->fetchAll(PDO::FETCH_ASSOC);

		// update user working variables with database data
		foreach ($user_db[0] as $param => $value) {
			$user[$param] = $value;
		}
		
		// query database for additional guests in group
		try {
			$guest_query = $db->prepare("SELECT GuestId, FirstName, LastName, GroupId, Attending, Email, PlusOne FROM Guests WHERE (GroupId = :GroupId AND GuestId != :GuestId)");
			foreach ($user as $param => $value) {
				if (preg_match ('/.*Id$/', $param)) {
					$guest_query->bindValue(":".$param,$value,PDO::PARAM_INT);
				}
			}
			$guest_query->execute();
		} catch (Exception $e) {
			echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
			exit;
		}
		$guest_db = $guest_query->fetchAll(PDO::FETCH_ASSOC);
		
		// update guest working variables with database data
		foreach ($guest_db[0] as $param => $value) {
			$guest[$param] = $value;
		}
		
		// set session variables
		foreach ($user as $param => $value) {
			$_SESSION['user'][$param] = $value;
		}

		foreach ($guest as $param => $value) {
			$_SESSION['guest'][$param] = $value;
		}

		// if no data found redirect to invite not found
		if (empty($user_db)) {
		    header("location:rsvp_1.php?status=invite_notfound");
		    exit;
		}

		// if no data found redirect to step 2
		if (!empty($user_db)) {
		    header("location:rsvp_2.php");
		    exit;
		}
	}
}

$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

include("inc/header.php");
?>
    <div id="wrap" class="sub-font">

	<?php 
	// Check for validation errors
	if (!empty($errors)) {
		if (isset($errors['firstname'])) {
			echo "<p class='error sub-font'>Please enter your first name.</p>";
		}
		if (isset($errors['lastname'])) {
			echo "<p class='error sub-font'>Please enter your last name.</p>";
		}
	} 

    ?>

    <div id='form_wrap' <?php if (!empty($errors) OR (isset($_GET["status"]))) { echo "class='state1'"; } ?>>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="rsvp_form">
        	
			<?php 
			// output for invitation not found
			if (isset($_GET["status"]) && $_GET["status"] == "invite_notfound") {
				echo "<p class='form_header'>Sorry, your invitation was not found.  Please try again.</p>";
				echo "<p class='form_header'>Enter your name exactly as it appears on your invitation.</p>";
			} 
			?>

			<div class="form_header">
        		<p class='sub-font form_header'>Enter your name to search for your invitation.</p>	
        	</div>	

           	<label for="firstname" class="step1">First Name: </label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($user['FirstName']);?>" />
            <label for="lastname">Last Name: </label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($user['LastName']);?>" />
			<textarea style="display:none" id="address" name="address"></textarea>
			<p  style="display:none">Please leave this field blank.</p>
            <input type="submit" name ="submit" value="Find Invitation" />

        </form>
		</div>
    </div>

</body>
</html>

<?php 

?>