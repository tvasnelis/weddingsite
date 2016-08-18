<?php

$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

require_once("inc/config.php");
require(ROOT_PATH . "inc/database.php");
require(ROOT_PATH . "inc/functions.php");
require(ROOT_PATH . "inc/guest.php");

// intialize error varaible
$errors = array();
$guests = array();

// read POST form data for form step m1
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_1'])) {

  // validate form data and read values
  if (empty($_POST['user_firstname'])) {
      $errors["user_firstname"] = "Please enter your first name";
    } else {
      // set session first name after trim and proper case
      $user_firstname = (ucwords(strtolower(trim(filter_input(INPUT_POST, "user_firstname", FILTER_SANITIZE_STRING)))));
    }

    if (empty($_POST['user_lastname'])) {
        $errors["user_lastname"] = "Please enter your last name";
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
      $user_data_query = $db->prepare("SELECT GuestId, FirstName, LastName, GroupId, Email, PlusOne, PlusFamily FROM Guests WHERE FirstName = :FirstName AND LastName = :LastName");
      $user_data_query->bindValue(':FirstName',$user_firstname, PDO::PARAM_STR);
      $user_data_query->bindValue(':LastName',$user_lastname, PDO::PARAM_STR);
      $user_data_query->execute();
    } catch (Exception $e) {
      echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
      exit;
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
      $guests[] = $user;
    }

    // query database for additional guests in group
    if (!empty($user_data)) {
  		try {
  			$guest_data_query = $db->prepare("SELECT GuestId, FirstName, LastName, GroupId, Email, PlusOne, PlusFamily FROM Guests WHERE (GroupId = :GroupId AND GuestId != :GuestId)");
        $guest_data_query->bindValue(":GroupId",$user->GroupId,PDO::PARAM_INT);
        $guest_data_query->bindValue(":GuestId",$user->GuestId,PDO::PARAM_INT);
  			$guest_data_query->execute();
  		} catch (Exception $e) {
  			echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
  			exit;
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
          $guests[] = $guest;
  			}
  		}
    }

    // query database for most recent rsvp for all guests
    if (!empty($guests)) {
			foreach ($guests as $key => $guest) {
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
				$guest_rsvp = $guest_rsvp_query->fetchAll(PDO::FETCH_ASSOC);
				// update guest with database data
				if (!empty($guest_rsvp)) {
					$guest->setAttending($guest_rsvp[0]['Attending']);
					$guest->setSubDate($guest_rsvp[0]['SubDate']);
					$guest->setRsvpUser($guest_rsvp[0]['FirstName'] . " " . $guest_RSVP[0]['LastName']);
				}
			}
		}

    // if no data found redirect to invite not found
		if (empty($guests)) {
		    header("location:?status=invite_notfound#rsvp");
		    exit;
		}

		}

}

?>
