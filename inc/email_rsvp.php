<?php

function email_rsvp($user, $group, $guests) {

	//require_once("inc/phpmailer/PHPMailerAutoload.php");

	$message  = "<html><body>";
	$message .= "<table width='100%' bgcolor='#93c2b2' cellpadding='0' cellspacing='0' border='0'>";  
	$message .= "<tr><td>"; 
	$message .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:700px; background-color:#fff; font-family:Montserrat, Geneva, sans-serif;'>";  
	$message .= "<thead>
	  <tr height='80'>
	  <th colspan='4' style='background-color:#fff; border-bottom:solid 1px #bdbdbd; font-family:Montserrat, Geneva, sans-serif; color:#000; font-size:24px;' >Success!</th>
	  </tr>
	             </thead>";    
	$message .= "<tbody>
	       <tr>
	       <td colspan='4' style='padding:15px;'>
	       <p style='font-size:16px;'>Hi ".$user->FirstName.",</p>
	       <p style='font-size:16px;'>
	       	Your RSVP has been received.  Please confirm that the information below is correct.
	       </p>";
	$message .= "<p>" . $user->FirstName . " " . $user->LastName .($user->Attending==1 ? " is attending." : " is not attending.");
	foreach ($group as $guest) {
		$message .= "<p>" . $guest->FirstName . " " . $guest->LastName .($guest->Attending==1 ? " is attending." : " is not attending.");
	}
	foreach ($guests as $guest) {
		$message .= "<p>" . $guest->FirstName . " " . $guest->LastName .($guest->Attending==1 ? " is attending." : " is not attending.");
	}

	$message .= "<p>Tim and Kimberly</p>";
	$message .= "<p><a href='www.timandkimberly.com' target='blank'>Wedding Homepage</a></p>";
	$message .= "<p><a href='mailto:rsvp@timandkimberly.com' target='_top'>Email Us</a></p>";


	echo $message;

	/*

	// define admin email address
	$email_admin = "rsvp@timandkimberly.com";

	// setup email to user 
	$mail_admin = new PHPMailer;

	// verify email address 
	if (!$mail_admin->ValidateAddress($user->Email)) {
		echo "invalid email address.";
		exit;
	}

	// setup email server 
	$mail_admin->IsSMTP();
	$mail_admin->SMTPAuth = true;
	$mail_admin->SMTPSecure = "ssl";
	$mail_admin->Host = "box831.bluehost.com";
	$mail_admin->Port = 465;
	$mail_admin->Username = $email_admin;
	$mail_admin->Password = "tkPass775";

	// setup email 
	$body_admin = "Test email. The following user has provided an RSVP via timandkimberly.com<br/>";
	$body_admin .= "Name " . $user->FirstName . " " . $user->LastName . " " . $user->Email . "  " . $user->Attending . "<br/>";
	foreach ($group as $guest) {
		$body_admin .= "Name: " . $guest->FirstName . " " . $guest->LastName . " Email: " . $guest->Email . " Attending: " . $guest->Attending . "<br/>";
	}
	foreach ($guests as $guest) {
		$body_admin .= "Name: " . $guest->FirstName . " " . $guest->LastName . " Email: " . $guest->Email . " Attending: " . $guest->Attending . "<br/>";
	}
	
	$mail_admin->setFrom($email_admin, "Tim and Kimberly");
	$mail_admin->addAddress($email_admin, "Tim and Kimberly"); // Add a recipient
	$mail_admin->Subject = 'Wedding RSVP from ' . $user->FirstName . " " . $user->LastName;
	$mail_admin->MsgHTML($message);

	// setup email to user 
	$mail_user = new PHPMailer;

	// setup email server 
	$mail_user->IsSMTP();
	$mail_user->SMTPAuth = true;
	$mail_user->SMTPSecure = "ssl";
	$mail_user->Host = "box831.bluehost.com";
	$mail_user->Port = 465;
	$mail_user->Username = $email_admin;
	$mail_user->Password = "tkPass775";

	// setup email 
	$body_user = "Test email. Thanks for your response!<br/>";
	$body_user .= "Name " . $user->FirstName . " " . $user->LastName . " " . $user->Email . "  " . $user->Attending . "<br/>";
	foreach ($group as $guest) {
		$body_admin .= "Name: " . $guest->FirstName . " " . $guest->LastName . " Email: " . $guest->Email . " Attending: " . $guest->Attending . "<br/>";
	}
	foreach ($guests as $guest) {
		$body_admin .= "Name: " . $guest->FirstName . " " . $guest->LastName . " Email: " . $guest->Email . " Attending: " . $guest->Attending . "<br/>";
	}

	$mail_user->setFrom($email_admin, "Tim and Kimberly");
	$mail_user->addAddress($user->Email, $user->FirstName . " " . $user->LastName); // Add a recipient
	foreach ($group as $guest) {
		if (!empty($guest->Email)) {
			$mail_user->addCC($guest->Email, $guest->FirstName . " " . $guest->LastName); // Add a recipient
		}
	}
	foreach ($guests as $guest) {
		if (!empty($guest->Email)) {
			$mail_user->addCC($guest->Email, $guest->FirstName . " " . $guest->LastName); // Add a recipient
		}
	}
	$mail_user->Subject = 'Tim and Kimberly Wedding RSVP';
	$mail_user->MsgHTML($message);                                 // Set email format to HTML


	// send emails 
	if(!$mail_admin->send() OR !$mail_user->send()) {
	    return false;
	} else {
		return true;
	}
*/
}

