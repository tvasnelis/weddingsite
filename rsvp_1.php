<?php 
require("inc/config.php");

require(ROOT_PATH . "inc/rsvp_session.php");
require(ROOT_PATH . "inc/database.php");
require(ROOT_PATH . "inc/email_rsvp.php");

// read POST form data for form 1
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	//check for errors from step 2 (if user used back button after creating step 2 errors) 
	if (isset($_SESSION['errors_2'])) {
		$_SESSION['errors_1'] = array();
		$_SESSION['errors_2'] = array();
	}

	// check for change to user and reset session variables
	if ($_SESSION['user']->issetFirstName() AND $_SESSION['user']->FirstName != trim(filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING))) {
		$temp = $_SESSION['counter'];
		$_SESSION = array();
		$_SESSION['user'] = new Guest;
		$_SESSION['errors_1'] = array();
		$_SESSION['errors_2'] = array();
		$_SESSION['newRsvp'] = true;
		$_SESSION['counter'] = $temp;
	} elseif ($_SESSION['user']->issetLastName() AND $_SESSION['user']->LastName != trim(filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING))) {
		$temp = $_SESSION['counter'];
		$_SESSION = array();
		$_SESSION['user'] = new Guest;
		$_SESSION['errors_1'] = array();
		$_SESSION['errors_2'] = array();
		$_SESSION['newRsvp'] = true;
		$_SESSION['counter'] = $temp;
	}

	// validate form data and read values
	if (empty($_POST['firstname'])) {
		$_SESSION['errors_1']['firstname'] = true;
    } else {
    	// set session first name after trim and proper case
    	$_SESSION['user']->setFirstName(ucwords(strtolower(trim(filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING)))));
    	unset($_SESSION['errors_1']['firstname']);
    }

    if (empty($_POST['lastname'])) {
        $_SESSION['errors_1']['lastname'] = true;
    } else {
    	// set session last name after trim and proper case
    	$_SESSION['user']->setLastName(ucwords(strtolower(trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING)))));
    	unset($_SESSION['errors_1']['lastname']);
    }

    // check for input on hidden form field
	if ($_POST['address'] != "") {
		echo "Bad form input.";
		exit;
	}

	// if no validation error query database
	if (empty($_SESSION['errors_1'])) {
		
		// query database on first and last name return Guest info for user
		try {
			$user_query = $db->prepare("SELECT GuestId, FirstName, LastName, GroupId, Email, PlusOne, PlusFamily FROM Guests WHERE FirstName = :FirstName AND LastName = :LastName");
			foreach ($_SESSION['user'] as $param => $value) {
				if (preg_match ('/.*Name$/', $param)) {
					$user_query->bindValue(":".$param,$value,PDO::PARAM_STR);
				}
			}		
			$user_query->execute();
		} catch (Exception $e) {
			echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
			exit;
		} 
		$user_tblGuests = $user_query->fetchAll(PDO::FETCH_ASSOC);

		// update user session variables with database data
		if (!empty($user_tblGuests)) {
			foreach ($_SESSION['user'] as $param => $value) {
				if (isset($user_tblGuests[0][$param])) {
					$_SESSION['user']->$param = $user_tblGuests[0][$param];
				}
			}
		}

		// query database for most recent user rsvp
		try {
			$user_rsvp_query = $db->prepare("SELECT RSVP.Attending, Guests.FirstName, Guests.LastName, RSVP.SubDate FROM Guests JOIN RSVP ON Guests.GuestID = RSVP.UserId WHERE RSVP.GuestId = :GuestId ORDER BY RSVP.SubDate DESC LIMIT 1");
			foreach ($_SESSION['user'] as $param => $value) {
				$user_rsvp_query->bindValue(":GuestId",$_SESSION['user']->GuestId,PDO::PARAM_STR);	
			}	
			$user_rsvp_query->execute();
		} catch (Exception $e) {
			echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
			exit;
		} 
		$user_RSVP = $user_rsvp_query->fetchAll(PDO::FETCH_ASSOC);

		// update user session variables with database data
		if (!empty($user_RSVP)) {
			$_SESSION['user']->setAttending($user_RSVP[0]['Attending']);
			$_SESSION['user']->setSubDate($user_RSVP[0]['SubDate']);
			$_SESSION['user']->setRsvpUser($user_RSVP[0]['FirstName'] . " " . $user_RSVP[0]['LastName']);
			$_SESSION['newRsvp'] = false;
		}


		// query database for additional guests in group
		try {
			$guest_query = $db->prepare("SELECT GuestId, FirstName, LastName, GroupId, Email, PlusOne, PlusFamily FROM Guests WHERE (GroupId = :GroupId AND GuestId != :GuestId)");
			foreach ($_SESSION['user'] as $param => $value) {
				if (preg_match ('/.*Id$/', $param)) {
					$guest_query->bindValue(":".$param,$value,PDO::PARAM_INT);
				}
			}
			$guest_query->execute();
		} catch (Exception $e) {
			echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
			exit;
		}
		$guest_tblGuests = $guest_query->fetchAll(PDO::FETCH_ASSOC);

		$groupCnt = count($guest_tblGuests);
		// instantiate new Guest for each new guest found
		if (!empty($guest_tblGuests)) {
			foreach ($guest_tblGuests as $key => $data) {
				$_SESSION["group"][$key] = new Guest;
				foreach ($data as $param => $value) {
					$_SESSION["group"][$key]->$param = $value;
				}
			}	
		}

		// query database for most recent rsvp for other group members
		if (isset($_SESSION['group'])) {
			foreach ($_SESSION['group'] as $key => $guest) {
				try {
					$guest_rsvp_query = $db->prepare("SELECT RSVP.Attending, Guests.FirstName, Guests.LastName, RSVP.SubDate FROM Guests JOIN RSVP ON Guests.GuestID = RSVP.UserId WHERE RSVP.GuestId = :GuestId ORDER BY RSVP.SubDate DESC LIMIT 1");
					foreach ($guest as $param => $value) {
						if ($param == "GuestId") {
							$guest_rsvp_query->bindValue(":GuestId",$value,PDO::PARAM_STR);
						}		
					}

					$guest_rsvp_query->execute();
				} catch (Exception $e) {
					echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
					exit;
				} 
				$guest_RSVP = $guest_rsvp_query->fetchAll(PDO::FETCH_ASSOC);
				// update user session variables with database data
				if (!empty($guest_RSVP)) {
					$guest->setAttending($guest_RSVP[0]['Attending']);
					$guest->setSubDate($guest_RSVP[0]['SubDate']);
					$guest->setRsvpUser($guest_RSVP[0]['FirstName'] . " " . $user_RSVP[0]['LastName']);
				}
			}		
		}
		
		// if no data found redirect to invite not found
		if (empty($user_tblGuests)) {
		    header("location:rsvp_1.php?status=invite_notfound");
		    exit;
		}

		// if no data found redirect to step 2
		if (!empty($user_tblGuests)) {
		    header("location:rsvp_2.php");
		    exit;
		}
	}
}

// check for form refresh
if (isset($_GET['submit'])) {
	$old_submit = $_GET['submit'];
	$current_url = "rsvp_1.php";
	if ($old_submit == "true") {
		header("Location: $current_url");
		$old_submit = “false”;
	}
}

$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

include("inc/header.php");
?>
    <div id="wrap" class="sub-font">

	<?php 
	// Check for validation errors and output error messages
	if (!empty($_SESSION['errors_1'])) {
		echo "<p class='error sub-font'>Please enter your first and last name.<br></p>";
	} 

    ?>

    <div id='form_wrap' <?php if (!empty($_SESSION['errors_1']) OR (isset($_GET["status"]))) { echo "class='state1'"; } ?>>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?submit=true"; ?>" id="rsvp_form">
        	<div class="form_header">
				<?php 
				// output for invitation not found
				if (isset($_GET["status"]) && $_GET["status"] == "invite_notfound") {
					echo "<p class='form_header'>I can't find you!  Please try again.</p>";
					echo "<p class='form_header'>Enter your name exactly as it appears on your invitation.</p>";
				} else {
					echo "<p class='sub-font form_header'>Enter your name to search for your invitation.</p>";
				}
				?>
        	</div>	

           	<label for="firstname" class="step1">First Name: </label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($_SESSION['user']->getFirstName());?>" />
            <label for="lastname">Last Name: </label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($_SESSION['user']->getLastName());?>" />
			<textarea style="display:none" id="address" name="address"></textarea>
			<p  style="display:none">Please leave this field blank.</p>
            <input type="submit" name ="submit" value="Find Invitation" />

        </form>
		</div>
    </div>

</body>
</html>

<?php 
//echo var_dump($_SESSION);
?>