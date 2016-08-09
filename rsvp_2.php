<?php
require("inc/config.php");

require(ROOT_PATH . "inc/rsvp_session.php");
require(ROOT_PATH . "inc/database.php");
require(ROOT_PATH . "inc/email_rsvp.php");

// read POST form data for form 2
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

	if ($_POST["formid"] == $_SESSION["formid"]) {

	    // update SESSION variable POST attending data for user
		if (array_key_exists('user_attending', $_POST) AND !empty($_POST['user_attending'])) {
	    	$_SESSION['user']->SetAttending($_POST['user_attending']);
	    	unset($_SESSION['errors_2']['user_attending']);
	    } else {
	    	$_SESSION['errors_2']['user_attending'] = true;
	    }
	    // update SESSION variable POST attending data for invited guests
		if (isset($_POST['guests'])) {
			foreach ($_POST['guests'] as $id => $guest) {
				if (isset($guest['attending'])) {
					foreach ($_SESSION['group'] as $indv) {
						$indvId = $indv->GuestId;
						if ($indvId == $id) {
				    		$indv->setAttending($guest['attending']);
				    	}
			    	}
			    	unset ($_SESSION['errors_2']['guest_attending'][$id]);
			    } else {
			    	$_SESSION['errors_2']['guest_attending'][$id] = true;
			    }
			    if (empty($_SESSION['errors_2']['guest_attending'])) {
			    	unset($_SESSION['errors_2']['guest_attending']);
			    }
			}
		}	
		
	    // validate form data and read values
	    // validate email and update SESSION variable for user
		if (!empty($_POST["user_email"]) AND filter_input(INPUT_POST, "user_email", FILTER_VALIDATE_EMAIL))  {
	        $_SESSION['user']->setEmail(trim(filter_input(INPUT_POST, "user_email", FILTER_SANITIZE_EMAIL)));
	        unset($_SESSION['errors_2']['user_email']);
	    } else {
	    	$_SESSION['errors_2']['user_email'] = true;  
	    }

	    // validate email and update SESSION variable for invited guests
	    if (isset($_POST['guests'])) {
		    foreach ($_POST['guests'] as $id => $guest) {
		    	if (!empty($guest['email'])) {
			    	if (filter_var($guest['email'], FILTER_VALIDATE_EMAIL)) {
			    		foreach ($_SESSION['group'] as $indv) {
				        	$indvId = $indv->GuestId;
				        	if ($indvId == $id) {
				        		$indv->setEmail(trim(filter_var($guest['email'], FILTER_SANITIZE_EMAIL)));
				        	} 
				        }
				        unset($_SESSION['errors_2']['guest_email'][$id]);
			    	} else {
			    		$_SESSION['errors_2']['guest_email'][$id] = true;
			    	}
			    } else {
			    	foreach ($_SESSION['group'] as $indv) {
				        $indvId = $indv->GuestId;
				       	if ($indvId == $id) {
				        	$indv->setEmail("");
				       	} 
				    }
			    	unset($_SESSION['errors_2']['guest_email'][$id]);
			    }
		    }
		    if (empty($_SESSION['errors_2']['guest_email'])) {
		    	unset($_SESSION['errors_2']['guest_email']);
		    }
		}

	   	// validate PlusOne info
	   	if (isset($_POST['PlusOne_attending'])) {
	   		$PlusOne['attending'] = $_POST['PlusOne_attending'];
	   		if ($PlusOne['attending'] == 1) {
	   			if (!empty($_POST["PlusOne_firstname"])) {
				    $PlusOne['firstname'] = ucwords(strtolower(trim(filter_input(INPUT_POST, "PlusOne_firstname", FILTER_SANITIZE_STRING))));
				    unset($_SESSION['errors_2']['PlusOne_firstname']);
				} else {
					$_SESSION['errors_2']['PlusOne_firstname'] = true;
				}

				if (!empty($_POST["PlusOne_lastname"])) {
				    $PlusOne['lastname'] = ucwords(strtolower(trim(filter_input(INPUT_POST, "PlusOne_lastname", FILTER_SANITIZE_STRING))));
				    unset($_SESSION['errors_2']['PlusOne_lastname']);
				} else {
					$_SESSION['errors_2']['PlusOne_lastname'] = true;
				}

				if (!empty($_POST["PlusOne_email"])) {
					if (filter_input(INPUT_POST, "PlusOne_email", FILTER_VALIDATE_EMAIL))  {
						$PlusOne['email'] = trim(filter_input(INPUT_POST, "PlusOne_email", FILTER_SANITIZE_EMAIL));
						unset($_SESSION['errors_2']['PlusOne_email']);
					} else {
						$_SESSION['errors_2']['PlusOne_email'] = true;
					}
				} else {
					$PlusOne['email'] = "";
					unset($_SESSION['errors_2']['PlusOne_email']);
				}
	   		}
				
	   	}

	   	// validate family info
	   	if (isset($_POST['family_cnt'])) {
	   		$_SESSION['temp']['family_cnt'] = $_POST['family_cnt'];
	   	} else {
	   		$_SESSION['temp']['family_cnt'] = 0;
	   	}

	 	for ($i = 1; $i <= $_SESSION['temp']['family_cnt']; $i++) {
	 		if (!empty($_POST['family'][$i]['firstname'])) {
	 			$family[$i]['firstname'] = ucwords(strtolower(trim(filter_var($_POST['family'][$i]['firstname'], FILTER_SANITIZE_STRING))));
	 			unset($_SESSION['errors_2']['family_firstname'][$i]);
	 		} else {
				$_SESSION['errors_2']['family_firstname'][$i] = true;
			}
			if (!empty($_POST['family'][$i]['lastname'])) {
	 			$family[$i]['lastname'] = ucwords(strtolower(trim(filter_var($_POST['family'][$i]['lastname'], FILTER_SANITIZE_STRING))));
	 			unset($_SESSION['errors_2']['family_lastname'][$i]);
	 		} else {
				$_SESSION['errors_2']['family_lastname'][$i] = true;
			}
			if (!empty($_POST['family'][$i]['email'])) {
				if (filter_var($_POST['family'][$i]['email'], FILTER_VALIDATE_EMAIL))  {
					$family[$i]['email'] = trim(filter_var($_POST['family'][$i]['email'], FILTER_SANITIZE_EMAIL));
					unset($_SESSION['errors_2']['family_email'][$i]);
				} else {
					$_SESSION['errors_2']['family_email'][$i] = true;

				}
			} else {
				$family[$i]['email'] = "";
				unset($_SESSION['errors_2']['family_email'][$i]);
			}
			if (empty($_SESSION['errors_2']['family_firstname'])) {
				unset($_SESSION['errors_2']['family_firstname']);
			}
			if (empty($_SESSION['errors_2']['family_lastname'])) {
				unset($_SESSION['errors_2']['family_lastname']);
			}
			if (empty($_SESSION['errors_2']['family_email'])) {
				unset($_SESSION['errors_2']['family_email']);
			}
	 	}

	    // check for input on hidden form field
		if ($_POST["address2"] != "") {
			echo "Bad form input";
			exit;
		}

		// store PlusOne and Family data in temporary SESSION variable for error case
		if (isset($PlusOne)) {
			$_SESSION['temp']['PlusOne'] = $PlusOne;
		}
		if (isset($family)) {
			foreach ($family as $id => $guest) {
			$_SESSION['temp']['family'][$id] = $guest;
			}
		}
		
		// if no validation error query database
		if (empty($_SESSION['errors_2'])) {

			// Clear temporary SESSION variable
			unset($_SESSION['temp']);

			$toDb = array();
			// Instantiate new guest for plus one
			if (isset($PlusOne)) {
				$guest_PlusOne = new Guest;
				$guest_PlusOne->SetFirstName($PlusOne['firstname']);
				$guest_PlusOne->SetLastName($PlusOne['lastname']);
				$guest_PlusOne->SetEmail($PlusOne['email']);
				$guest_PlusOne->SetGroupId($_SESSION['user']->GroupId);
				$guest_PlusOne->SetAttending('1');
				$guest_PlusOne->SetPlusFamily('0');
				$guest_PlusOne->SetPlusOne('0');
				$toDb[] = $guest_PlusOne;
				$_SESSION['user']->setPlusOne('0');
			}

			// Instantiate new guests for family
			if (!empty($family)) {
				foreach($family as $guest) {
					$guest_family = new Guest;
					$guest_family->SetFirstName($guest['firstname']);
					$guest_family->SetLastName($guest['lastname']);
					$guest_family->SetEmail($guest['email']);
					$guest_family->SetGroupId($_SESSION['user']->GroupId);
					$guest_family->SetAttending('1');
					$guest_family->SetPlusFamily('0');
					$guest_family->SetPlusOne('0');
					$toDb[] = $guest_family;
				}
				$_SESSION['user']->setPlusFamily('0');
				if (isset($_SESSION['group'])) {
					foreach($_SESSION['group'] as $guest) {
						$guest->setPlusFamily('0');
					}
				}
			}
																																									
			//update database with session variables for user and invited guests
			$sql = array();

			// Create SQL queries to update user info and create RSVP entry
			$sql[] = "UPDATE Guests SET Email='" . $_SESSION['user']->Email . "' WHERE GuestId=" . $_SESSION['user']->GuestId;
			$sql[] = "UPDATE Guests SET PlusOne=" . $_SESSION['user']->PlusOne . " WHERE GuestId=" . $_SESSION['user']->GuestId;
			$sql[] = "UPDATE Guests SET PlusFamily=" . $_SESSION['user']->PlusFamily . " WHERE GuestId=" . $_SESSION['user']->GuestId;
			$sql[] = "INSERT INTO RSVP (GuestId, Attending, UserId, SubDate) VALUES (" . $_SESSION['user']->GuestId . ", " . $_SESSION['user']->Attending . ", " . $_SESSION['user']->GuestId . ", NOW())" ;

			// Create SQL queries to update guest email and create RSVP entries
			if (isset($_SESSION['group'])) {
				foreach ($_SESSION['group'] as $guest) {
					$sql[] = "UPDATE Guests SET Email='" . $guest->Email . "' WHERE GuestId=" . $guest->GuestId;
					$sql[] = "UPDATE Guests SET PlusOne=" . $_SESSION['user']->PlusOne . " WHERE GuestId=" . $guest->GuestId;
					$sql[] = "UPDATE Guests SET PlusFamily=" . $_SESSION['user']->PlusFamily . " WHERE GuestId=" . $guest->GuestId;
					$sql[] = "INSERT INTO RSVP (GuestId, Attending, UserId, SubDate) VALUES (" . $guest->GuestId . ", " . $guest->Attending . ", " . $_SESSION['user']->GuestId . ", NOW())" ;
				}
			}

			// create new guests in database for Plus One and family
			if (isset($toDb)) {
				foreach ($toDb as $guest) {
					$stmt = "INSERT INTO Guests (FirstName, LastName, GroupId, PlusOne, PlusFamily) VALUES ('" . $guest->FirstName . "', '" . $guest->LastName . "', " . $guest->GroupId . ", " . $guest->PlusOne . ", " . $guest->PlusFamily . ")";	
					try {
						$sqlstmt = $db->prepare($stmt);
						$sqlstmt->execute();
						$guest->SetGuestId($db->lastInsertId());
						$sql[] = "UPDATE Guests SET Email='" . $guest->Email . "' WHERE GuestId=" . $guest->GuestId; 
						$sql[] = "INSERT INTO RSVP (GuestId, Attending, UserId, SubDate) VALUES (" . $guest->GuestId . ", " . $guest->Attending . ", " . $_SESSION['user']->GuestId . ", NOW())" ;
					} catch (Exception $e) {
						$pageTitle = "Tim & Kimberly - RSVP";
						$section = "rsvp";
						//include("inc/header.php");
						echo "<link rel='stylesheet type='text/css href='css/rsvp.css'>";
						echo "<p class='error sub-font'>Error writing to database. Please try again later.</p>";
						exit;
					}
				}				
			}

			// Execute Queries
			foreach ($sql as $stmt) {
				try {
					$sqlstmt = $db->prepare($stmt);
					$sqlstmt->execute();
				} catch (Exception $e) {
					$pageTitle = "Tim & Kimberly - RSVP";
					$section = "rsvp";
					//include("inc/header.php");
					echo "<link rel='stylesheet type='text/css href='css/rsvp.css'>";
					echo "<p class='error sub-font'>Error writing to database. Please try again later.</p>";
					exit;
				}
			}

			// send email to user	
			if (!email_rsvp($_SESSION['user'],$_SESSION['group'],$toDb)) {
				echo 'Message could not be sent.' . '</br>';
			    exit();
			} else {
				header("location:thanks");
				session_destroy();
				exit();
			}
		}
	}

} else {
	$_SESSION["formid"] = md5(rand(0,10000000));
}

$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

include("inc/header.php");
?>
 <div id="wrap" class="sub-font">
	<?php 

	echo "<div id='error_wrap'>";
		// Check for validation errors
		if (!empty($_SESSION['errors_2'])) {
			if (isset($_SESSION['errors_2']['user_attending']) OR isset($_SESSION['errors_2']['guest_attending'])) {
				echo "<p class='error sub-font'>Please specify In or Out for all guests.</p>";
			}
			if (isset($_SESSION['errors_2']['user_email'])) {
				echo "<p class='error sub-font'>Please enter a valid email address.</p>";
			}
			if (isset($_SESSION['errors_2']['guest_email']) OR isset($_SESSION['errors_2']['PlusOne_email']) OR !empty($_SESSION['errors_2']['family_email'])) {
				echo "<p class='error sub-font'>Please make sure all email addresses are valid (or leave blank).</p>";
			}
			if (isset($_SESSION['errors_2']['PlusOne_firstname']) OR isset($_SESSION['errors_2']['PlusOne_lastname']) OR isset($_SESSION['errors_2']['family_firstname']) OR isset($_SESSION['errors']['family_lastname'])) {
				echo "<p class='error sub-font'>Please enter a first and last name for all guests.</p>";
			}

		}
	echo "</div>"; 
	
	// Thank you page text
	if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
        		echo "<h3 class='const'>Thank You!</h3>";
    } else { ?>

    <div id='form_wrap' class='default'} ?>
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
        		<p class='sub-font old_rsvp form_header'>Your previous response was found!</p>	
        		<p class='sub-font old_rsvp form_header'>Make changes below.</p>
        	</div>

        	<div class="form_body">
        		<p class='sub-font new_rsvp'>Please provide an email address for confirmation.</p>
        		<label for="user_email" class="email">Email: </label>
            	<input type="text" name="user_email" id="user_email" value="<?php htmlspecialchars($_SESSION['user']->getEmail());?>" class=<?php echo "'email"; if (isset($_SESSION['errors_2']['user_email'])) {echo " error_border";} echo "'"?>><br>
        		<p class='sub-font'>Your invitation includes the following guests.  Who's in?</p>
        	
				<?php 
				// display rsvp for user
				$user_fname = $_SESSION['user']->FirstName;
				$user_lname = $_SESSION['user']->LastName;
				?>

				<label for="<?php echo htmlspecialchars($user_fname . $user_lname . "Att") ?>" class="name"><?php htmlspecialchars($_SESSION['user']->getFullName())?></label>
				<input type="radio" class="att" id="<?php echo htmlspecialchars($user_fname . $user_lname . "Att") ?>" value="1" name="user_attending" <?php if ($_SESSION['user']->isAttending()) {echo " checked='checked'";} ?>>
				<label for="<?php echo htmlspecialchars($user_fname . $user_lname . 'Att') ?>" class="form_button">In</label>
				<input type="radio" class="att" id="<?php echo htmlspecialchars($user_fname . $user_lname . "NotAtt"); ?>" value="0" name="user_attending" <?php if ($_SESSION['user']->isNotAttending()) {echo " checked='checked'";} ?>>
				<label for="<?php echo htmlspecialchars($user_fname . $user_lname . 'NotAtt') ?>" class="form_button" >Out</label><br>
					
            	
				
				<?php
				// display rsvp for guests
				$guest_counter=1;
				if (!empty($_SESSION['group'])) { 
					foreach ($_SESSION['group'] as $guest) {
						$guest_fname = $guest->FirstName;
						$guest_lname = $guest->LastName;
						$guest_fullname = $guest->FirstName . " " . $guest->LastName;
						$guest_id = $guest->GuestId;
						$guest_email = $guest->Email;
				?>
				<label for="<?php echo htmlspecialchars($guest_fname . $guest_lname . "Att") ?>" class="name"><?php echo htmlspecialchars($guest_fullname) ?></label> 
				<input type="radio" class="att" id="<?php echo htmlspecialchars($guest_fname . $guest_lname . "Att") ?>" class="" <?php if ($guest->isAttending()) {echo " checked='checked'";} ?>value="1" name="guests[<?php echo htmlspecialchars($guest_id)?>][attending] ?>" >
				<label for="<?php echo htmlspecialchars($guest_fname . $guest_lname . "Att") ?>" class="form_button" >In</label>
				<input type="radio" class="att" id="<?php echo htmlspecialchars($guest_fname . $guest_lname . "NotAtt"); ?>" <?php if ($guest->isNotAttending()) {echo " checked='checked'";} ?>value="0" name="guests[<?php echo htmlspecialchars($guest_id)?>][attending] ?>" >
				<label for="<?php echo htmlspecialchars($guest_fname . $guest_lname . "NotAtt") ?>" class="form_button">Out</label><br>
				<!-- <label for="<?php echo htmlspecialchars($guest_id . "_email") ?>" class="email">Email: </label> -->
				<!-- <input type="text" name="guests[<?php echo htmlspecialchars($guest_id)?>][email] ?>" id="<?php echo htmlspecialchars($guest_id . "_email") ?>" value="<?php echo htmlspecialchars($guest_email); ?>" class=<?php echo "'email"; if (isset($_SESSION['errors_2']['guest_email'][$guest_id])) {echo " error_border";} echo "'"?>><br> -->
				<?php
				$guest_counter += 1;
				echo "<script> guestClass(" . $guest_counter. "); </script>";
					} 
				}
				
				// display rsvp for Plus One
				if ($_SESSION['user']->PlusOne) {
				?>
				<label for="PlusOneAtt" class="name">Guest</label> 
				<input type="radio" class="att" id="PlusOneAtt" class="" value="1" name="PlusOne_attending" onclick='javascript:plusOneCheck();' <?php if (isset($_SESSION['temp']['PlusOne']) AND $_SESSION['temp']['PlusOne']['attending'] == 1) {echo " checked='checked'";} ?>>
				<label for="PlusOneAtt" class="form_button">In</label>
				<input type="radio" class="att" id="PlusOneNotAtt" value="0" name="PlusOne_attending" onclick='javascript:plusOneCheck();' <?php if (isset($_SESSION['temp']['PlusOne']) AND $_SESSION['temp']['PlusOne']['attending'] == 0) {echo " checked='checked'";} ?>>
				<label for="PlusOneNotAtt" class="form_button">Out</label><br>
				<div id="PlusOne_info" style="display:none">
	            	<p class='sub-font'>Please enter your guest's name.</p>	
	            	<label for="PlusOne_firstname" class="guest_name">First: </label>
        			<input type="text" name="PlusOne_firstname" id="PlusOne_firstname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['PlusOne_firstname'])) {echo " error_border";} echo "'"?> value="<?php if (isset($_SESSION['temp']['PlusOne']['firstname'])) {echo $_SESSION['temp']['PlusOne']['firstname'];} ?>" />
        			<label for="PlusOne_lastname" class="guest_name">Last: </label>
        			<input type="text" name="PlusOne_lastname" id="PlusOne_lastname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['PlusOne_lastname'])) {echo " error_border";} echo "'"?> value="<?php if (isset($_SESSION['temp']['PlusOne']['lastname'])) {echo $_SESSION['temp']['PlusOne']['lastname'];} ?>" />
        			<!-- <label for="PlusOne_email">Email: </label> -->
        			<!-- <input type="text" name="PlusOne_email" id="PlusOne_email" class="email <?php echo htmlspecialchars($guest_email); ?>" class=<?php echo "'email"; if (isset($_SESSION['errors_2']['PlusOne_email'])) {echo " error_border";} echo "'"?> value="<?php if (isset($_SESSION['temp']['PlusOne']['email'])) {echo $_SESSION['temp']['PlusOne']['email'];} ?>"/>  -->
	        	</div>
				<?php
				$guest_counter += 1; 
				echo "<script> guestClass(" . $guest_counter. "); </script>";				
				}

				// display rsvp for Plus Family
				if ($_SESSION['user']->PlusFamily) { 
					echo "<script> plusClass(); </script>";
					echo "<script> plusClass(); </script>";
					echo "<script> plusClass(); </script>";
					echo "<script> plusClass(); </script>";
					echo "<script> plusClass(); </script>";
					echo "<script> plusClass(); </script>";
					echo "<script> plusClass(); </script>";
					echo "<script> plusClass(); </script>";
					echo "<script> plusClass(); </script>";
					echo "<p class='sub-font'>Your invitation includes your family.</p>";
					echo "<p class='sub-font'>How many additional guests are attending?</p>";
					echo "<input id='family_cnt_0' type='radio' value='0' name='family_cnt' onclick='javascript:familyCheck();'";
						if (isset($_SESSION['temp']['family_cnt']) AND $_SESSION['temp']['family_cnt'] == 0) {echo " checked='checked'";}
						echo ">0";
					echo "<input id='family_cnt_1' type='radio' value='1' name='family_cnt' onclick='javascript:familyCheck();'";
						if (isset($_SESSION['temp']['family_cnt']) AND $_SESSION['temp']['family_cnt'] == 1) {echo " checked='checked'";}
						echo ">1";
					echo "<input id='family_cnt_2' type='radio' value='2' name='family_cnt' onclick='javascript:familyCheck();'";
						if (isset($_SESSION['temp']['family_cnt']) AND $_SESSION['temp']['family_cnt'] == 2) {echo " checked='checked'";} 
						echo ">2";
					echo "<input id='family_cnt_3' type='radio' value='3' name='family_cnt' onclick='javascript:familyCheck();'";
						if (isset($_SESSION['temp']['family_cnt']) AND $_SESSION['temp']['family_cnt'] == 3) {echo " checked='checked'";} 
						echo ">3";
					echo "<input id='family_cnt_4' type='radio' value='4' name='family_cnt' onclick='javascript:familyCheck();'";
						if (isset($_SESSION['temp']['family_cnt']) AND $_SESSION['temp']['family_cnt'] == 4) {echo " checked='checked'";} 
						echo ">4";
				?>	

						<div id="ifOne" style="display:none">
					        <p class='sub-font'>Please enter your guests' names.</p>	
			            	<label for="family_1_firstname" class="guest_name">First: </label>
	            			<input type="text" name="family[1][firstname]" id="family_1_firstname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['family_firstname'][1])) {echo " error_border";} echo "'"?>
	            				value="<?php if (isset($_SESSION['temp']['family'][1]['firstname'])) {echo $_SESSION['temp']['family'][1]['firstname'];} ?>" />
	            			<label for="family_1_lastname" class="guest_name">Last: </label>
	            			<input type="text" name="family[1][lastname]" id="family_1_lastname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['family_lastname'][1])) {echo " error_border";} echo "'"?>
	            				value="<?php if (isset($_SESSION['temp']['family'][1]['lastname'])) {echo $_SESSION['temp']['family'][1]['lastname'];} ?>" />
	            			<?php /*
	            			<label for="family_1_email">Email: </label>
	            			<input type="text" name="family[1][email]" id="family_1_email" class="email<?php if (isset($_SESSION['errors_2']['user_email'])) {echo " error_border";} ?>"
	            				value="<?php if (isset($_SESSION['temp']['family'][1]['email'])) {echo $_SESSION['temp']['family'][1]['email'];} ?>"/><br>
							*/ ?>
					    </div>
            			<div id="ifTwo" style="display:none">
			            	<label for="family_2_firstname" class="guest_name">First: </label>
	            			<input type="text" name="family[2][firstname]" id="family_2_firstname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['family_firstname'][2])) {echo " error_border";} echo "'"?>
	            				value="<?php if (isset($_SESSION['temp']['family'][2]['firstname'])) {echo $_SESSION['temp']['family'][2]['firstname'];} ?>" />
	            			<label for="family_2_lastname" class="guest_name">Last: </label>
	            			<input type="text" name="family[2][lastname]" id="family_2_lastname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['family_lastname'][2])) {echo " error_border";} echo "'"?>
	            				value="<?php if (isset($_SESSION['temp']['family'][2]['lastname'])) {echo $_SESSION['temp']['family'][2]['lastname'];} ?>" />
	            			<?php /*
	            			<label for="family_2_email">Email: </label>
	            			<input type="text" name="family[2][email]" id="family_2_email" class="email"
	            				<?php if (isset($_SESSION['errors_2']['family_email'][2])) {echo "style='border: 1px solid red'";} ?>
	            				value="<?php if (isset($_SESSION['temp']['family'][2]['email'])) {echo $_SESSION['temp']['family'][2]['email'];} ?>"/><br>
	            			*/ ?>
					    </div>
					    <div id="ifThree" style="display:none">
			            	<label for="family_3_firstname" class="guest_name">First: </label>
	            			<input type="text" name="family[3][firstname]" id="family_3_firstname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['family_firstname'][3])) {echo " error_border";} echo "'"?>
	            				value="<?php if (isset($_SESSION['temp']['family'][3]['firstname'])) {echo $_SESSION['temp']['family'][3]['firstname'];} ?>" />
	            			<label for="family_1_lastname" class="guest_name">Last: </label>
	            			<input type="text" name="family[3][lastname]" id="family_3_lastname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['family_lastname'][3])) {echo " error_border";} echo "'"?>
	            				value="<?php if (isset($_SESSION['temp']['family'][3]['lastname'])) {echo $_SESSION['temp']['family'][3]['lastname'];} ?>" />
	            			<?php /*
	            			<label for="family_3_email">Email: </label>
	            			<input type="text" name="family[3][email]" id="family_3_email" class="email<?php if (isset($_SESSION['errors_2']['user_email'])) {echo " error_border";} ?>"

	            				value="<?php if (isset($_SESSION['temp']['family'][3]['email'])) {echo $_SESSION['temp']['family'][3]['email'];} ?>"/><br>
							*/ ?>
					    </div>
					    <div id="ifFour" style="display:none">
			            	<label for="family_4_firstname" class="guest_name">First: </label>
	            			<input type="text" name="family[4][firstname]" id="family_4_firstname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['family_firstname'][4])) {echo " error_border";} echo "'"?>
	            				value="<?php if (isset($_SESSION['temp']['family'][4]['firstname'])) {echo $_SESSION['temp']['family'][4]['firstname'];} ?>" />
	            			<label for="family_4_lastname" class="guest_name">Last: </label>
	            			<input type="text" name="family[4][lastname]" id="family_4_lastname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['family_lastname'][4])) {echo " error_border";} echo "'"?>
	            				value="<?php if (isset($_SESSION['temp']['family'][4]['lastname'])) {echo $_SESSION['temp']['family'][4]['lastname'];} ?>" />
	            			<?php /*
	            			<label for="family_4_email">Email: </label>
	            			<input type="text" name="family[4][email]" id="family_4_email" class="email<?php if (isset($_SESSION['errors_2']['user_email'])) {echo " error_border";} ?>"
	            				value="<?php if (isset($_SESSION['temp']['family'][4]['email'])) {echo $_SESSION['temp']['family'][4]['email'];} ?>"/><br>
							*/ ?>
					    </div>
				<?php
				}?>
		        <textarea style="display:none" id="address" name="address2"></textarea>
				<p  style="display:none">Please leave this field blank.</p>

				<input type="hidden" name="formid" value="<?php echo $_SESSION["formid"]; ?>" />

		        <input type="submit" name ="submit" value="Submit" class="rsvp_2"/>
		    </div>
	<?php } ?>

        </form>
    </div>
<script> plusOneCheck(); </script>
<script> familyCheck(); </script>
</body>
</html>
