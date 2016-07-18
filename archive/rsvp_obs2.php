<?php 

include("inc/email_rsvp.php");

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
$error_form1 = false;
$error_form2 = false;

// initialize session variables
if (!isset($_GET["status"])) {
//	$_SESSION['results'] = array();
	$_SESSION['firstname'] = "";
	$_SESSION['lastname'] = "";
	$_SESSION['email_user'] = "";
}

// read POST form data for SUBMIT 1 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit1'])) {

	// validate form data and read values
	if (empty($_POST["firstname"])) {
        $error_form1 = true;
    } else {
    	$firstname = trim(filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING));
    	$firstname = ucwords(strtolower($firstname));
    }

    if (empty($_POST["lastname"])) {
        $error_form1 = true;
    } else {
    	$lastname = trim(filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING));
    	$lastname = ucwords(strtolower($lastname));
    }

    // check for input on hidden form field
	if ($_POST["address"] != "") {
		echo "Bad form input";
		exit;
	}

	// if no validation error query database
	if (!$error_form1) {

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

		// query database for user id (first and last)
		try {
			$user = $db->prepare("SELECT GuestId FROM Guests WHERE FirstName = :firstname AND LastName = :lastname");
			$user->bindValue(':firstname', $firstname);
			$user->bindValue(':lastname', $lastname);
			$user->bindValue(':firstname', $firstname);
			$user->bindValue(':lastname', $lastname);
			$user->execute();
		} catch (Exception $e) {
			echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
			exit;
		}
		

		// query database for info on all guests in group
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
		$user_id = $user->fetchAll(PDO::FETCH_ASSOC);
		foreach ($user_id as $key => $user) {
			$_SESSION['id_user'] = $user['GuestId'];
		}
		$_SESSION['results'] = $results->fetchAll(PDO::FETCH_ASSOC);
		$_SESSION['firstname'] = $firstname;
		$_SESSION['lastname'] = $lastname;
		
		//foreach ($_SESSION['results'] as $guest) {
	    //	if ($guest["FirstName"] == $firstname AND $guest["LastName"] == $lastname) {
	    //		$_SESSION['email_user'] = $guest['Email'];
	    //	}
	    //}

		// if no data found redirect to invite not found
		if (empty($_SESSION['results'])) {
		    header("location:rsvp.php?status=invite_notfound");
		    exit;
		}

		// if no data found redirect to step 2
		if (!empty($_SESSION['results'])) {
		    header("location:rsvp.php?status=invite_found");
		    exit;
		}
	}
}

// read POST form data for SUBMIT 2 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2'])) {

	// update session results variable with post attending data
    foreach ($_SESSION['results'] as $key => $guest) {
    	$id = $guest["GuestId"];	
    	if (array_key_exists($id, $_POST)) {
    		$_SESSION['results'][$key]["Attending"] = $_POST[$id];
    	}
    }

    // validate form data and read values
    $firstname_guest = "Guest";
    $lastname_guest = "";
    $email_guest = "";
	if (!empty($_POST["firstname_guest"])) {
    	$firstname_guest = trim(filter_input(INPUT_POST, "firstname_guest", FILTER_SANITIZE_STRING));
    	$firstname_guest = ucwords(strtolower($firstname_guest));
    }

    if (!empty($_POST["firstname_guest"])) {
    	$lastname_guest = trim(filter_input(INPUT_POST, "lastname_guest", FILTER_SANITIZE_STRING));
    	$lastname_guest = ucwords(strtolower($lastname_guest));
    }

    if (!empty($_POST["email_guest"])) {
    	if (!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))  {
    		$error_form2 = true;
    	} else {
    		$email_guest = trim(filter_input(INPUT_POST, "email_guest", FILTER_SANITIZE_EMAIL));
    	}
    }

    // validate email address
	if (empty($_POST["email"]) OR !filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))  {
        $error_form2 = true;
    }
    else {
        $email_user = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));;
    }

    // check for input on hidden form field
	if ($_POST["address2"] != "") {
		echo "Bad form input";
		exit;
	}


    // if no validation error query database
	if (!$error_form2) {

		// update session variables with email and guest data
		$_SESSION['email_user'] = $email_user;
    	foreach ($_SESSION['results'] as $key => $guest) {
    		$id = $guest["GuestId"];
    		if ($id == $_SESSION['id_user']) {
    			$_SESSION['results'][$key]["Email"] = $email_user;
    		} else {
    			$_SESSION['results'][$key]["FirstName"] = $firstname_guest;
    			$_SESSION['results'][$key]["LastName"] = $lastname_guest;
    			$_SESSION['results'][$key]["Email"] = $email_guest;
    		}
    		if (array_key_exists($id, $_POST)) {
    			$_SESSION['results'][$key]["Attending"] = $_POST[$id];
    				
    		}
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
																																																
		//update database with session results data
		
		foreach ($_SESSION['results'] as $guest) {	
			$sql = array();
			$sql[] = "UPDATE Guests SET Attending=" . $guest["Attending"] . " WHERE GuestId=" . $guest["GuestId"];
			$sql[] = "UPDATE Guests SET Email='" . $guest["Email"] . "' WHERE GuestId=" . $guest["GuestId"];
			if ($guest['GuestId'] != $_SESSION['id_user']) {
				$sql[] = "UPDATE Guests SET FirstName='" . $firstname_guest . "' WHERE GuestId=" . $guest["GuestId"];
				$sql[] = "UPDATE Guests SET LastName='" . $lastname_guest . "' WHERE GuestId=" . $guest["GuestId"];
				$sql[] = "UPDATE Guests SET Email='" . $email_guest . "' WHERE GuestId=" . $guest["GuestId"];
			}
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
		}

		header("location:rsvp.php?status=thanks");
		//session_destroy()
		exit;

	}

	foreach ($_SESSION['results'] as $guest) {
		email_rsvp($guest);
	}
	
	
}

$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

include("inc/header.php");
?>
<link rel="stylesheet" type="text/css" href="css/rsvp.css">

<script type="text/javascript">

	jQuery.fn.extend({
    live: function (event, callback) {
	       if (this.selector) {
	            jQuery(document).on(event, this.selector, callback);
	        }
	    }
	});

	$('#Guest').live('change', function(){
		if ( $(this).is(':checked') ) {
			$('#guest_info').show();
		} else {
			$('#guest_info').hide();
		}
	});
</script>

    <div id="wrap" class="sub-font">
	<?php 

	// Validation errors statement(s)
	if ($error_form1) {
		echo "<p class='error sub-font'>Please enter your first and last name.</p>";
	}

	if ($error_form2) {
		echo "<p class='error sub-font'>Please provide a valid email address.</p>";
	}
	
	// Thank you page text
	if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
        		echo "<p>Thank You!</p>";
    } else { ?>

    <div id='form_wrap' <?php if ($error_form1 OR $error_form2 OR (isset($_GET["status"]))) { echo "class='state1'"; } ?>>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="rsvp_form">
        	
			<?php 
			// output for invitation not found
			if (isset($_GET["status"]) && $_GET["status"] == "invite_notfound") {
				echo "<p class='form_header'>Sorry, your invitation was not found.  Please try again.</p>";
				echo "<p class='form_header'>Enter your name exactly as it appears on your invitation.</p>";
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

				<div class="form_header">
	        		<p class='sub-font form_header'>Enter your name to search for your invitation.</p>	
	        	</div>	

	           	<label for="firstname" class="step1">First Name: </label>
	            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($_SESSION['firstname']);?>" />
	            <label for="lastname">Last Name: </label>
	            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($_SESSION['lastname']);?>" />
				<textarea style="display:none" id="address" name="address"></textarea>
				<p  style="display:none">Please leave this field blank.</p>
	            <input type="submit" name ="submit1" value="Find Invitation" />
	         </div>

	        <?php if ((isset($_GET["status"]) && $_GET["status"] == "invite_found") OR ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2']))) {
        		echo "<style>";
        		echo "#step1 {display: none}";
        		echo "#step2 {display: block}";
        		echo "</style>";
        	} ?>

        	<div id="step2">
        		<?php
        		// check if first submission
        		$new_rsvp = true;
        		foreach ($_SESSION['results'] as $guest) {
        			if (!is_null($guest['Attending'])) {
        				$new_rsvp = false;
        			}
        		}

        		// set display state for elements based on new_rsvp
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
		        	<label for="email">Email Address: </label>
	            	<input type="text" name="email" id="email" value="<?php echo htmlspecialchars($_SESSION['email_user']);?>"/>

	            	<p class='sub-font new_rsvp'>Please mark the checkbox for all guests that are attending.</p>	

					<?php 
					// display check box for each guest and set up array for result
					$i = 1;
					foreach ($_SESSION['results'] as $guest) {
						$id = $guest["GuestId"];
						$fname = $guest['FirstName'];
						$lname = $guest['LastName'];
						$att = 	$guest['Attending'];
					?>

						<input id="<?php echo $fname . $lname . 'hidden' ?>" type="hidden" value="0" name="<?php echo $id ?>">
						<input id="<?php echo $fname . $lname ?>" type="checkbox" class="roundedOne" value="1" name="<?php echo $id ?>" <?php if ($att==1) {echo " checked='checked'";} ?>>
						<label for="guests[]" ><?php echo $fname . " " . $lname ?></label><br>

		        	<?php } ?>
		        	
	            	<div id="guest_info" style="display:none">
		            	<p class='sub-font'>Please enter guest information (optional).</p>	
		            	<label for="firstname_guest">First Name: </label>
		            	<input type="text" name="firstname_guest" id="firstname_guest" value=""/>
		            	<label for="lastname_guest">Last Name: </label>
		            	<input type="text" name="lastname_guest" id="lastname_guest" value=""/>
		            	<label for="email_guest">Email Address: </label>
		            	<input type="text" name="email_guest" id="email_guest" value=""/>
			        	
			        </div>

			        <textarea style="display:none" id="address2" name="address2"></textarea>
					<p  style="display:none">Please leave this field blank.</p>

			        <input type="submit" name ="submit2" value="Submit" />
			    </div>
	        </div>
	<?php } ?>

        </form>
		</div>
    </div>

</body>
</html>

<?php 

//echo var_dump($_SESSION['results']); 
//echo var_dump($_SESSION['id_user']);

?>