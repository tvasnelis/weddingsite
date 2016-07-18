<?php 

include("inc/email_rsvp.php");
include("inc/rsvp_session.php");

// copy session variables to local working variables
$guests = array(
	'user' => array(
		'GuestId' => $_SESSION['user']['GuestId'],
		'FirstName' => $_SESSION['user']['FirstName'],
		'LastName' => $_SESSION['user']['LastName'],
		'GroupId' => $_SESSION['user']['GroupId'],
		'Attending' => $_SESSION['user']['Attending'],
		'Email' => $_SESSION['user']['Email'],
		'PlusOne' => $_SESSION['user']['PlusOne']
	),
	'guest' => array(
		'GuestId' => $_SESSION['guest']['GuestId'],
		'FirstName' => $_SESSION['guest']['FirstName'],
		'LastName' => $_SESSION['guest']['LastName'],
		'GroupId' => $_SESSION['guest']['GroupId'],
		'Attending' => $_SESSION['guest']['Attending'],
		'Email' => $_SESSION['guest']['Email'],
		'PlusOne' => $_SESSION['guest']['PlusOne']
	)
);


$errors = $_SESSION['errors'];

// read POST form data for form 2
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	// update guest and session variable with post attending data
	foreach ($guests as $param => $indv) {
		$id = $indv['GuestId'];
		if (array_key_exists($id, $_POST)) {
    		$guests[$param]['Attending'] = $_POST[$id];
    		$_SESSION[$param]['Attending'] = $guests[$param]['Attending'];
    	}
	}

    // validate form data and read values
	if (!empty($_POST["guest_firstname"])) {
    	$guests['guest']['FirstName'] = trim(filter_input(INPUT_POST, "guest_firstname", FILTER_SANITIZE_STRING));
    	$guests['guest']['FirstName'] = ucwords(strtolower($guests['guest']['FirstName']));
    }

    if (!empty($_POST["guest_firstname"])) {
    	$guests['guest']['LastName'] = trim(filter_input(INPUT_POST, "guest_lastname", FILTER_SANITIZE_STRING));
    	$guests['guest']['LastName'] = ucwords(strtolower($guests['guest']['LastName']));
    }

    if (!empty($_POST["guest_email"])) {
    	if (!filter_input(INPUT_POST, "guest_email", FILTER_VALIDATE_EMAIL))  {
    		$errors["guest_email"] = true;
    	} else {
    		$guests['guest']['Email'] = trim(filter_input(INPUT_POST, "guest_email", FILTER_SANITIZE_EMAIL));
    		unset($errors["guest_email"]);
    	}
    }

    // validate email address
	if (empty($_POST["user_email"]) OR !filter_input(INPUT_POST, "user_email", FILTER_VALIDATE_EMAIL))  {
        $errors["user_email"] = true;
    }
    else {
        $guests['user']['Email'] = trim(filter_input(INPUT_POST, "user_email", FILTER_SANITIZE_EMAIL));
        unset($errors["user_email"]);
    }

    // check for input on hidden form field
	if ($_POST["address2"] != "") {
		echo "Bad form input";
		exit;
	}

	$_SESSION['errors'] = $errors;
	// set session variables
	foreach ($guests as $param => $indv) {
		$_SESSION[$param]['FirstName'] = $guests[$param]['FirstName'];
		$_SESSION[$param]['LastName'] = $guests[$param]['LastName'];
		$_SESSION[$param]['Email'] = $guests[$param]['Email'];
	}

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
																																																
		//update database with session data
		$sql = array();
		$sql[] = "UPDATE Guests SET Email='" . $_SESSION['user']['Email'] . "' WHERE GuestId=" . $_SESSION['user']['GuestId'];
		$sql[] = "UPDATE Guests SET Attending=" . $_SESSION['user']['Attending'] . " WHERE GuestId=" . $_SESSION['user']['GuestId'];
		$sql[] = "UPDATE Guests SET FirstName='" . $_SESSION['guest']['FirstName'] . "' WHERE GuestId=" . $_SESSION['guest']['GuestId'];
		$sql[] = "UPDATE Guests SET LastName='" . $_SESSION['guest']['LastName'] . "' WHERE GuestId=" . $_SESSION['guest']['GuestId'];
		$sql[] = "UPDATE Guests SET Attending=" . $_SESSION['guest']['Attending'] . " WHERE GuestId=" . $_SESSION['guest']['GuestId'];
		$sql[] = "UPDATE Guests SET Email='" . $_SESSION['guest']['Email'] . "' WHERE GuestId=" . $_SESSION['guest']['GuestId'];

		foreach ($sql as $stmt) {
			try {
				$sqlstmt = $db->prepare($stmt);
				$sqlstmt ->execute();
			} catch (Exception $e) {
				$pageTitle = "Tim & Kimberly - RSVP";
				$section = "rsvp";
				include("inc/header.php");
				echo "<link rel='stylesheet type='text/css href='css/rsvp.css'>";
				echo "<p class='error sub-font'>Error writing to database. Please try again later.</p>";
				exit;
			}
		}


		if (!email_rsvp($_SESSION['user'],$_SESSION['guest'])) {
			echo 'Message could not be sent.' . '</br>';
		    echo 'Mailer Error Admin: ' . $mail_admin->ErrorInfo . '</br>';
		    echo 'Mailer Error User: ' . $mail_user->ErrorInfo;
		    exit();
		} else {
			header("location:rsvp_2.php?status=thanks");
			$_SESSION=array();
			session_destroy();
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
		if (isset($errors['user_email']) OR isset($errors['guest_email'])) {
			echo "<p class='error sub-font'>Please enter a valid email address.</p>";
		}
	} 
	
	// Thank you page text
	if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
        		echo "<h3 class='const'>Thank You!</h3>";
    } else { ?>

    <div id='form_wrap' class='state1'} ?>>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="rsvp_form">

    		<?php
    		// check if first submission
    		$new_rsvp = true;
    		if (!is_null($guests['user']['Attending']) OR !is_null($guests['guest']['Attending'])) {
    				$new_rsvp = false;
    		}

    		//set display state for elements based on new_rsvp
    		if (!$new_rsvp) {
    			echo "<style>";
				echo ".new_rsvp {display: none}";
				echo ".old_rsvp {display: block}";
				echo "</style>";
    		}  else {
    			echo "<style>";
    			echo ".new_rsvp {display: block}";
				echo ".old_rsvp {display: none}";
				echo "</style>";
    		}
    		?>

    		<div class="form_header">
        		<p class='sub-font new_rsvp form_header'>Your invitation was found!</p>	

        		<p class='sub-font old_rsvp form_header'>Your previous RSVP was found!</p>	
        		<p class='sub-font old_rsvp form_header'>Make changes below.</p>
        	</div>

        	<div class="form_body">
        		<p class='sub-font new_rsvp'>Please provide an email address for confirmation and updates.</p>	
	        	<label for="user_email">Email Address: </label>
            	<input type="text" name="user_email" id="user_email" value="<?php echo htmlspecialchars($guests['user']['Email']);?>"/>

            	<p class='sub-font new_rsvp'>Please mark the checkbox for all guests that are attending.</p>	

				<?php 
				// display check box for each guest and set up array for result
				foreach ($guests as $indv) {
					$id = $indv["GuestId"];
					if ($indv['PlusOne'] == 1) {
						$fname = "Guest";
						$lname = "";
						echo "<style>#guest_info {display: block;}</style>";
					} else {
						$fname = $indv['FirstName'];
						$lname = $indv['LastName'];
						echo "<style>#guest_info {display: none;}</style>";
					}
					$att = 	$indv['Attending'];
				?>

					<input id="<?php echo $fname . $lname . 'hidden' ?>" type="hidden" value="0" name="<?php echo $id ?>">
					<input id="<?php echo $fname . $lname ?>" type="checkbox" class="" value="1" name="<?php echo $id ?>" <?php if ($att==1) {echo " checked='checked'";} ?>>
					<label for="guests[]" ><?php echo $fname . " " . $lname ?></label><br>

	        	<?php } ?>
	        	
            	<div id="guest_info" >
            		<!-- style="display:none" -->
	            	<p class='sub-font'>Please enter guest information (optional).</p>	
	            	<label for="guest_firstname">First Name: </label>
	            	<input type="text" name="guest_firstname" id="guest_firstname" value="<?php echo htmlspecialchars($guests['guest']['FirstName']);?>"/>
	            	<label for="guest_lastname">Last Name: </label>
	            	<input type="text" name="guest_lastname" id="guest_lastname" value="<?php echo htmlspecialchars($guests['guest']['LastName']);?>"/>
	            	<label for="guest_email">Email Address: </label>
	            	<input type="text" name="guest_email" id="guest_email" value="<?php echo htmlspecialchars($guests['guest']['Email']);?>"/>
		        	
		        </div>

		        <textarea style="display:none" id="address" name="address2"></textarea>
				<p  style="display:none">Please leave this field blank.</p>

		        <input type="submit" name ="submit" value="Submit" />
		    </div>
	<?php } ?>

        </form>
		</div>
    </div>

</body>
</html>

<?php 

?>