<?php

$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

require_once("inc/config.php");
require(ROOT_PATH . "inc/database.php");
require(ROOT_PATH . "inc/email_rsvp.php");
require(ROOT_PATH . "inc/functions.php");
require(ROOT_PATH . "inc/guest.php");
require(ROOT_PATH . "inc/rsvp_session.php");

// intialize error varaible
$errors = array();

$errorMessage_name = 'Please enter your first and last name';
$errorMessage_email = 'Please enter a valid email address';
$errorMessage_guestname = 'Please enter a first and last name for all guests';
$errorMessage_guestatt = 'Please mark In/Out for all guests';
$errorMessage_databaseconnection = 'Error connecting to database';

// if not GET status set and step 1 has been completed
// destroy current session and start a new session
// (prevents back button from interfering up form order)
if ((!isset($_GET['status']) && isset($_SESSION['invite_found'])) && $_SERVER["REQUEST_METHOD"] != 'POST') {
  session_destroy();
  include(ROOT_PATH . 'inc/rsvp_session.php');
}

// read POST form data for form step m1
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_1'])) {

  if ($_POST['formid'] == $_SESSION['formid']) {

    // validate form data and read values
    if (empty($_POST['user_firstname'])) {
        $errors['user_firstname'] = true;
      } else {
        // set session first name after trim and proper case
        $user_firstname = (ucwords(strtolower(trim(filter_input(INPUT_POST, "user_firstname", FILTER_SANITIZE_STRING)))));
      }

      if (empty($_POST['user_lastname'])) {
          $errors['user_lastname'] = true;
      } else {
        // set session last name after trim and proper case
        $user_lastname = (ucwords(strtolower(trim(filter_input(INPUT_POST, 'user_lastname', FILTER_SANITIZE_STRING)))));
      }

      // check for input on hidden form field
    if ($_POST['address_1'] != "") {
      echo "Bad form input.";
      exit;
    }

    // if no validation error query database
    if (empty($errors)) {

      // query database on first and last name return Guest info for user
      try {
        $user_data_query = $db->prepare('SELECT GuestId, FirstName, LastName, GroupId, Email, PlusOne, PlusFamily FROM Guests WHERE FirstName = :FirstName AND LastName = :LastName');
        $user_data_query->bindValue(':FirstName',$user_firstname, PDO::PARAM_STR);
        $user_data_query->bindValue(':LastName',$user_lastname, PDO::PARAM_STR);
        $user_data_query->execute();
      } catch (Exception $e) {
        $errors['database'] = true;
      }
      $user_data = $user_data_query->fetchAll(PDO::FETCH_ASSOC);

      //if user found, instantiate new guest for user and populate data
      if (!empty($user_data)) {
        $user = new Guest;
        $user->SetGuestId($user_data[0]["GuestId"]);
        $user->SetFirstName($user_data[0]["FirstName"]);
        $user->SetLastName($user_data[0]["LastName"]);
        $user->SetGroupId($user_data[0]["GroupId"]);
        $user->SetEmail($user_data[0]["Email"]);
        $user->SetPlusOne($user_data[0]["PlusOne"]);
        $user->SetPlusFamily($user_data[0]["PlusFamily"]);
        $_SESSION["guests"][] = $user;
      }

      // query database for additional guests in group
      if (!empty($user_data)) {
    		try {
    			$guest_data_query = $db->prepare('SELECT GuestId, FirstName, LastName, GroupId, Email, PlusOne, PlusFamily FROM Guests WHERE (GroupId = :GroupId AND GuestId != :GuestId)');
          $guest_data_query->bindValue(":GroupId",$user->GroupId,PDO::PARAM_INT);
          $guest_data_query->bindValue(":GuestId",$user->GuestId,PDO::PARAM_INT);
    			$guest_data_query->execute();
    		} catch (Exception $e) {
    			$errors['database'] = true;
    		}
    		$guest_data = $guest_data_query->fetchAll(PDO::FETCH_ASSOC);

        $groupCnt = count($guest_data);
    		// instantiate new Guest for each new guest found
    		if (!empty($guest_data)) {
    			foreach ($guest_data as $key => $data) {
    				$guest = new Guest;
    				foreach ($data as $param => $value) {
    					$guest->$param = $value;
    				}
            $_SESSION['guests'][] = $guest;
    			}
    		}
      }

      // query database for most recent rsvp for all guests
      if (!empty($_SESSION['guests'])) {
  			foreach ($_SESSION['guests'] as $key => $guest) {
  				try {
  					$guest_rsvp_query = $db->prepare('SELECT RSVP.Attending, Guests.FirstName, Guests.LastName, RSVP.SubDate FROM Guests JOIN RSVP ON Guests.GuestID = RSVP.UserId WHERE RSVP.GuestId = :GuestId ORDER BY RSVP.SubDate DESC LIMIT 1');
  					foreach ($guest as $param => $value) {
  						if ($param == 'GuestId') {
  							$guest_rsvp_query->bindValue(':GuestId',$value,PDO::PARAM_STR);
  						}
  					}
  					$guest_rsvp_query->execute();
  				} catch (Exception $e) {
  					$errors['database'] = true;
  				}
  				$guest_rsvp = $guest_rsvp_query->fetchAll(PDO::FETCH_ASSOC);
  				// update guest with database data
  				if (!empty($guest_rsvp)) {
  					$guest->setAttending($guest_rsvp[0]['Attending']);
  					$guest->setSubDate($guest_rsvp[0]['SubDate']);
  					$guest->setRsvpUser($guest_rsvp[0]['FirstName'] . " " . $guest_rsvp[0]['LastName']);
  				}
  			}
  		}

      // if no data found redirect to invite not found
  		if (empty($_SESSION['guests'])) {
  		  header('location:invite_notfound#rsvp');
  		  exit;
  		} else {
        $_SESSION['invite_found'] = true;
        header('location:invite_found');
        exit;
      }

  	}
  }
} else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit_2'])) {
  if ($_POST['formid'] == $_SESSION["formid"]) {
    // update SESSION guests variable with POST attending data for invited guests
    if (!empty($_SESSION['guests'])) {
      foreach ($_SESSION['guests'] as $guest) {
        $GuestId = $guest->GuestId;
        if (isset($_POST['guests'][$GuestId]['attending'])) {
          $guest->SetAttending($_POST['guests'][$GuestId]['attending']);
          unset($errors['guest_attending'][$GuestId]);
        } else {
          $errors['guest_attending'][$GuestId] = true;
        }
      }
    }
    if (empty($errors['guest_attending'])) {
      unset($errors['guest_attending']);
    }


    // validate form data and read values
    // validate email and update SESSION variable for user
    if (!empty($_POST["user_email"]) && filter_input(INPUT_POST, "user_email", FILTER_VALIDATE_EMAIL))  {
      $_SESSION['guests'][0]->setEmail(trim(filter_input(INPUT_POST, "user_email", FILTER_SANITIZE_EMAIL)));
      unset($errors['user_email']);
    } else {
      $errors['user_email'] = true;  
    } 

    // validate PlusOne info
    if (isset($_POST['PlusOne_attending'])) {
      $PlusOne['attending'] = $_POST['PlusOne_attending'];
      if ($PlusOne['attending'] == 1) {
        if (!empty($_POST["PlusOne_firstname"])) {
          $PlusOne['firstname'] = ucwords(strtolower(trim(filter_input(INPUT_POST, "PlusOne_firstname", FILTER_SANITIZE_STRING))));
          unset($errors['PlusOne_firstname']);
        } else {
          $errors['PlusOne_firstname'] = true;
        }
        if (!empty($_POST["PlusOne_lastname"])) {
          $PlusOne['lastname'] = ucwords(strtolower(trim(filter_input(INPUT_POST, "PlusOne_lastname", FILTER_SANITIZE_STRING))));
          unset($errors['PlusOne_lastname']);
        } else {
          $errors['PlusOne_lastname'] = true;
        }
      }
    }

    // validate family info
    if (isset($_POST['family_cnt'])) {
      $family_cnt = $_POST['family_cnt'];
    } else {
      $family_cnt = 0;
    }

    for ($i = 1; $i <= $family_cnt; $i++) {
      if (!empty($_POST['family'][$i]['firstname'])) {
        $family[$i]['firstname'] = ucwords(strtolower(trim(filter_var($_POST['family'][$i]['firstname'], FILTER_SANITIZE_STRING))));
        unset($errors['family_firstname'][$i]);
      } else {
        $errors['family_firstname'][$i] = true;
      }
      if (!empty($_POST['family'][$i]['lastname'])) {
        $family[$i]['lastname'] = ucwords(strtolower(trim(filter_var($_POST['family'][$i]['lastname'], FILTER_SANITIZE_STRING))));
        unset($errors['family_lastname'][$i]);
      } else {
        $errors['family_lastname'][$i] = true;
      }
      if (empty($errors['family_firstname'])) {
        unset($errors['family_firstname']);
      }
      if (empty($errors['family_lastname'])) {
        unset($errors['family_lastname']);
      }
    }

    // check for input on hidden form field
    if ($_POST["address_2"] != "") {
      echo "Bad form input";
      exit;
    }

    // if no validation error query database
    if (empty($errors)) {

      // Instantiate new guest for plus one
      if (isset($PlusOne)) {
        $guest_PlusOne = new Guest;
        $guest_PlusOne->SetFirstName($PlusOne['firstname']);
        $guest_PlusOne->SetLastName($PlusOne['lastname']);
        $guest_PlusOne->SetGroupId($_SESSION['guests'][0]->GroupId);
        $guest_PlusOne->SetAttending($PlusOne['attending']);
        $guest_PlusOne->SetPlusFamily('0');
        $guest_PlusOne->SetPlusOne('0');
        $_SESSION['guests'][] = $guest_PlusOne;
        $_SESSION['guests'][0]->setPlusOne('0');
      }

      // Instantiate new guests for family
      if (!empty($family)) {
        if (isset($_SESSION['guests'])) {
          foreach($_SESSION['guests'] as $guest) {
            $guest->setPlusFamily('0');
          }
        }
        foreach($family as $guest) {
          $guest_family = new Guest;
          $guest_family->SetFirstName($guest['firstname']);
          $guest_family->SetLastName($guest['lastname']);
          $guest_family->SetGroupId($_SESSION['guests'][0]->GroupId);
          $guest_family->SetAttending('1');
          $guest_family->SetPlusFamily('0');
          $guest_family->SetPlusOne('0');
          $_SESSION['guests'][] = $guest_family;
        }
        
      }

      //update database with session variables for user and invited guests
      $sql = array();

      // Create SQL queries to update user info and create RSVP entry
      $sql[] = "UPDATE Guests SET Email='" . $_SESSION['guests'][0]->Email . "' WHERE GuestId=" . $_SESSION['guests'][0]->GuestId;
      foreach ($_SESSION['guests'] as $guest) {
        if (!isset($guest->GuestId)) {
          $stmt = "INSERT INTO Guests (FirstName, LastName, GroupId, PlusOne, PlusFamily) VALUES ('" . $guest->FirstName . "', '" . $guest->LastName . "', " . $guest->GroupId . ", " . $guest->PlusOne . ", " . $guest->PlusFamily . ")";
          try {
            $sqlstmt = $db->prepare($stmt);
            $sqlstmt->execute();
            $guest->SetGuestId($db->lastInsertId());
          } catch (Exception $e) {
            error_message("Error writing to database. Please try again later.");
            exit;
          }
        } else {
          $stmt = "UPDATE Guests SET PlusFamily =" . $_SESSION['guests'][0]->PlusFamily . " WHERE GuestId=" . $guest->GuestId;
          $sql[]=$stmt;
          $stmt = "UPDATE Guests SET PlusOne =" . $_SESSION['guests'][0]->PlusOne . " WHERE GuestId=" . $guest->GuestId;
          $sql[]=$stmt;
        }
        $sql[] = "INSERT INTO RSVP (GuestId, Attending, UserId, SubDate) VALUES (" . $guest->GuestId . ", " . $guest->Attending . ", " . $_SESSION['guests'][0]->GuestId . ", NOW())" ;
      }

      // Execute Queries
      foreach ($sql as $stmt) {
        try {
          $sqlstmt = $db->prepare($stmt);
          $sqlstmt->execute();
        } catch (Exception $e) {
          error_message("Error writing to database. Please try again later.");
          exit;
        }
      }

      // // send email to user 
      if (!email_rsvp($_SESSION['guests'])) {
        error_message("Message could not be sent. Please try again later.");
          exit();
      } else {
        header("location:?status=thanks#rsvp");
        session_destroy();
        exit();
      }   
    }   
  }
} else {
  $_SESSION["formid"] = md5(rand(0,10000000));
}

?>


