<?php
require("inc/config.php");

require(ROOT_PATH . "inc/rsvp_session.php");
require(ROOT_PATH . "inc/database.php");
require(ROOT_PATH . "inc/email_rsvp.php");

// read POST form data for form 2
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
	echo var_dump($_POST);
	echo var_dump($_SESSION);

	// update session variable with POST attending data for user
	if (array_key_exists('user_attending', $_POST)) {
    	$_SESSION['user']->SetAttending($_POST['user_attending']);
    }
    // update session variable with POST attending data for invited guests
	foreach ($_SESSION['group'] as $guest) {
		$id = $guest->GuestId;
		if (array_key_exists($id . "_attending", $_POST)) {
    		$guest->setAttending($_POST[$id  . "_attending"]);
    	}
	}

    // validate form data and read values
    // validate user email
	if (empty($_POST["user_email"]) OR !filter_input(INPUT_POST, "user_email", FILTER_VALIDATE_EMAIL))  {
        $_SESSION['errors']['user_email'] = true;
    }
    else {
        $_SESSION['user']->setEmail(trim(filter_input(INPUT_POST, "user_email", FILTER_SANITIZE_EMAIL)));
        unset($_SESSION['errors']['user_email']);
    }

    // validate guest email
    foreach ($_POST['guests'] as $id => $guest) {
	    	if (!filter_var($guest['email'], FILTER_VALIDATE_EMAIL))  {
	        $_SESSION['errors']['guest_email'] = true;
	    }
	    else {
	        foreach ($_SESSION['group'] as $indv) {
	        	$indvId = $indv->GuestId;
	        	if ($indvId == $id) {
	        		$indv->setEmail($guest['email']);
	        	}
	        }
	        unset($_SESSION['errors']['guest_email']);
	    }
    }

   	// validate PlusOne info
   	if (isset($_POST['PlusOne_attending'])) {
		if (!empty($_POST["PlusOne_firstname"])) {
		    $PlusOne['firstname'] = ucwords(strtolower(trim(filter_input(INPUT_POST, "PlusOne_firstname", FILTER_SANITIZE_STRING))));
		    unset($_SESSION['errors']['PlusOne_firstname']);
		} else {
			$_SESSION['errors']['PlusOne_firstname'] = true;
		}

		if (!empty($_POST["PlusOne_lastname"])) {
		    $PlusOne['lastname'] = ucwords(strtolower(trim(filter_input(INPUT_POST, "PlusOne_lastname", FILTER_SANITIZE_STRING))));
		    unset($_SESSION['errors']['PlusOne_lastname']);
		} else {
			$_SESSION['errors']['PlusOne_lastname'] = true;
		}

		if (!empty($_POST["PlusOne_email"])) {
			if (!filter_input(INPUT_POST, "user_email", FILTER_VALIDATE_EMAIL))  {
				$_SESSION['errors']['PlusOne_email'] = true;
			} else {
				$PlusOne['email'] = trim(filter_input(INPUT_POST, "PlusOne_email", FILTER_SANITIZE_EMAIL));
			}
		}
   	}

   	//echo var_dump($PlusOne);
	
   	// validate family info
   	if (!empty($_POST['family'])) {
   		foreach ($_POST['family'] as $id => $guest) {
	   		if (!empty($guest['firstname'])) {
			    $family[$id]['firstname'] = ucwords(strtolower(trim(filter_var($guest['firstname'], FILTER_SANITIZE_STRING))));
			    unset($_SESSION['errors']['guest_firstname' . $id]);
			} else {
				$_SESSION['errors']['guest_firstname' . $id] = true;
			}
	   	}
	   	foreach ($_POST['family'] as $id => $guest) {
	   		if (!empty($guest['lastname'])) {
			    $family[$id]['lastname'] = ucwords(strtolower(trim(filter_var($guest['lastname'], FILTER_SANITIZE_STRING))));
			    unset($_SESSION['errors']['guest_lastname' . $id]);
			} else {
				$_SESSION['errors']['guest_lastname' . $id] = true;
			}
	   	}
   	}
   	
   	echo var_dump($family); 







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


		// TODO: Instantiate new guest for plus one

		// TODO: Instantiate new guests for family



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

//include("inc/header.php");
include("js/form_hide.js");
?>





 <div id="wrap" class="sub-font">
	<?php 

	// Check for validation errors
	//$_SESSION['errors']['user_email']
	//$_SESSION['errors']['guest_email']
	//$_SESSION['errors']['PlusOne_firstname']
	//$_SESSION['errors']['PlusOne_lastname']
	//$_SESSION['errors']['PlusOne_email']



	if (!empty($_SESSION['errors'])) {
		if (isset($_SESSION['errors']['user_email']) OR isset($_SESSION['errors']['guest_email'])) {
			echo "<p class='error sub-font'>Please enter a valid email address.</p>";
		}
	} 
	
	// Thank you page text
	if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
        		echo "<h3 class='const'>Thank You!</h3>";
    } else { ?>

    <div id='form_wrap' class='state1'} ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="rsvp_form">

    		<?php

    		//set display state for elements based on new_rsvp
    		if ($_SESSION['newRsvp']) {
    			echo "<style>";
				echo ".new_rsvp {display: block}";
				echo ".old_rsvp {display: none}";
				echo "</style>";
    		}  else {
    			echo "<style>";
    			echo ".new_rsvp {display: none}";
				echo ".old_rsvp {display: block}";
				echo "</style>";
    		}
    		?>

    		<div class="form_header">
        		<p class='sub-font new_rsvp form_header'>Your invitation was found!</p>	
        		<p class='sub-font old_rsvp form_header'>Your previous RSVP was found!</p>	
        		<p class='sub-font old_rsvp form_header'>Make changes for each guest below.</p>
        	</div>

        	<div class="form_body">
        		<p class='sub-font'>Please provide a primary email address for confirmation and updates.</p>
        		<label for="user_email">Email: </label>
            	<input type="text" name="user_email" id="user_email" value="<?php htmlspecialchars($_SESSION['user']->getEmail());?>"/><br>	
            	<!-- <p class='sub-font new_rsvp'>Please mark the checkbox for all guests that are attending.</p> -->	

				<?php 
				// display rsvp for user
				$user_fname = $_SESSION['user']->FirstName;
				$user_lname = $_SESSION['user']->LastName;
				?>
				<label for="<?php echo htmlspecialchars($user_fname . $user_lname . "Att") ?>" >
					<?php htmlspecialchars($_SESSION['user']->getFullName()) ?></label>
				<input type="radio" id="<?php echo htmlspecialchars($user_fname . $user_lname . "Att") ?>" class="" value="1" 
					name="user_attending" <?php if ($_SESSION['user']->isAttending()) {echo " checked='checked'";} ?>>
				<label for="<?php echo htmlspecialchars($user_fname . $user_lname . 'Att') ?>" >Attending</label>
				<input type="radio" id="<?php echo htmlspecialchars($user_fname . $user_lname . "NotAtt"); ?>" value="0" 
					name="user_attending"<?php if ($_SESSION['user']->isNotAttending()) {echo " checked='checked'";} ?>>
				<label for="<?php echo htmlspecialchars($user_fname . $user_lname . 'NotAtt') ?>" >Not Attending</label>
				<?php
				// display rsvp for guests
				if (!empty($_SESSION['group'])) { 
					echo "<p class='sub-font'>Your invitation includes the following guests.</p>";
					foreach ($_SESSION['group'] as $guest) {
						$guest_fname = $guest->FirstName;
						$guest_lname = $guest->LastName;
						$guest_fullname = $guest->FirstName . " " . $guest->LastName;
						$guest_id = $guest->GuestId;
				?>
						<label for="<?php echo htmlspecialchars($guest_fname . $guest_lname . "Att") ?>" ><?php echo htmlspecialchars($guest_fullname) ?></label> 
						<input type="radio" id="<?php echo htmlspecialchars($guest_fname . $guest_lname . "Att") ?>" class="" value="1" name="guests[<?php echo htmlspecialchars($guest_id)?>][attending] ?>" <?php if ($guest->isAttending()) {echo " checked='checked'";} ?>>
						<label for="<?php echo htmlspecialchars($guest_fname . $guest_lname . "Att") ?>" >Attending</label>
						<input type="radio" id="<?php echo htmlspecialchars($guest_fname . $guest_lname . "NotAtt"); ?>" value="0" name="guests[<?php echo htmlspecialchars($guest_id)?>][attending] ?>" <?php if ($guest->isNotAttending()) {echo " checked='checked'";} ?>>
						<label for="<?php echo htmlspecialchars($guest_fname . $guest_lname . "NotAtt") ?>" >Not Attending</label>
						<label for="<?php echo htmlspecialchars($guest_id . "_email") ?>">Email: </label>
						<input type="text" name="guests[<?php echo htmlspecialchars($guest_id)?>][email] ?>" id="<?php echo htmlspecialchars($guest_id . "_email") ?>"><br>
				<?php
					} 
				}
				// display rsvp for Plus One
				if ($_SESSION['user']->PlusOne) { 
					echo "<p class='sub-font'>Your invitation includes a guest.</p>";
				?>
					<label for="PlusOneAtt" >Guest</label> 
					<input id="PlusOneAtt" type="radio" class="" value="1" name="PlusOne_attending" onclick='javascript:plusOneCheck();'>
					<label for="PlusOneAtt">Attending</label>
					<input id="PlusOneNotAtt" type="radio" value="0" name="PlusOne_attending" onclick='javascript:plusOneCheck();'>
					<label for="PlusOneNotAtt">Not Attending</label><br>
					<div id="PlusOne_info" style="display:none">
	            		<!-- style="display:none" -->
		            	<p class='sub-font'>Please enter guest information (email is optional).</p>	
		            	<label for="PlusOne_firstname">First Name: </label>
            			<input type="text" name="PlusOne_firstname" id="PlusOne_firstname" value="" />
            			<label for="PlusOne_lastname">Last Name: </label>
            			<input type="text" name="PlusOne_lastname" id="PlusOne_lastname" value="" />
            			<label for="PlusOne_email">Email: </label>
            			<input type="text" name="PlusOne_email" id="PlusOne_email" value=""/>
		        	</div>
				<?php
				}
				// display rsvp for Plus Family
				if ($_SESSION['user']->PlusFamily) { 
					echo "<p class='sub-font'>Your invitation includes your family.</p>";
					echo "<p class='sub-font'>How many additional guests are attending?</p>";
					echo "<input id='family_cnt_1' type='radio' value='1' name='family_cnt' onclick='javascript:familyCheck();'>1";
					echo "<input id='family_cnt_2' type='radio' value='2' name='family_cnt' onclick='javascript:familyCheck();'>2";
					echo "<input id='family_cnt_3' type='radio' value='3' name='family_cnt' onclick='javascript:familyCheck();'>3";
					echo "<input id='family_cnt_4' type='radio' value='4' name='family_cnt' onclick='javascript:familyCheck();'>4";
				?>		
						<div id="ifOne" style="display:none">
					        <p class='sub-font'>Please enter guest information (email is optional).</p>	
			            	<label for="family_1_firstname">First Name: </label>
	            			<input type="text" name="family[1][firstname]" id="family_1_firstname" value="" />
	            			<label for="family_1_lastname">Last Name: </label>
	            			<input type="text" name="family[1][lastname]" id="family_1_lastname" value="" />
	            			<label for="family_1_email">Email: </label>
	            			<input type="text" name="family[1][email]" id="family_1_email" value=""/><br>
					    </div>
            			<div id="ifTwo" style="display:none">
			            	<label for="family_2_firstname">First Name: </label>
	            			<input type="text" name="family[2][firstname]" id="family_2_firstname" value="" />
	            			<label for="family_2_lastname">Last Name: </label>
	            			<input type="text" name="family[2][lastname]" id="family_2_lastname" value="" />
	            			<label for="family_2_email">Email: </label>
	            			<input type="text" name="family[2][email]" id="family_2_email" value=""/><br>
					    </div>
					    <div id="ifThree" style="display:none">
			            	<label for="family_3_firstname">First Name: </label>
	            			<input type="text" name="family[3][firstname]" id="family_3_firstname" value="" />
	            			<label for="family_1_lastname">Last Name: </label>
	            			<input type="text" name="family[3][lastname]" id="family_3_lastname" value="" />
	            			<label for="family_3_email">Email: </label>
	            			<input type="text" name="family[3][email]" id="family_3_email" value=""/><br>
					    </div>
					    <div id="ifFour" style="display:none">
			            	<label for="family_4_firstname">First Name: </label>
	            			<input type="text" name="family[4][firstname]" id="family_4_firstname" value="" />
	            			<label for="family_4_lastname">Last Name: </label>
	            			<input type="text" name="family[4][lastname]" id="family_4_lastname" value="" />
	            			<label for="family_4_email">Email: </label>
	            			<input type="text" name="family[4][email]" id="family_4_email" value=""/><br>
					    </div>
		        	</div>
				<?php
				}?>
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
echo var_dump($_SESSION);
?>