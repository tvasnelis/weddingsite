<?php

function email_rsvp($user, $guest) {
	require_once("inc/phpmailer/PHPMailerAutoload.php");

	// define admin email address
	$email_admin = "rsvp@timandkimberly.com";

	/* setup email to user */
	$mail_admin = new PHPMailer;

	/* verify email address */
	if (!$mail_admin->ValidateAddress($user['Email'])) {
		echo "invalid email address.";
		exit;
	}

	/* setup email server */
	$mail_admin->IsSMTP();
	$mail_admin->SMTPAuth = true;
	$mail_admin->SMTPSecure = "ssl";
	$mail_admin->Host = "box831.bluehost.com";
	$mail_admin->Port = 465;
	$mail_admin->Username = $email_admin;
	$mail_admin->Password = "tkPass775";

	/* setup email */
	$body_admin = "Test email. The following user has provided an RSVP via timandkimberly.com";
	$body_admin .= "Name " . $user['FirstName'] . " " . $user['LastName'] . " " . $user['Attending'] . "\n";
	$body_admin .= "Email " . $user['Email'] . "\n";
	$body_admin .= "\n";
	$body_admin .= "Name " . $guest['FirstName'] . " " . $guest['LastName'] . " " . $guest['Attending'] . "\n";
	$body_admin .= "Email " . $guest['Email'] . "\n";

	$mail_admin->setFrom($email_admin, "Tim and Kimberly");
	$mail_admin->addAddress($email_admin, "Tim and Kimberly"); // Add a recipient
	$mail_admin->Subject = 'Wedding RSVP from ' . $user['FirstName'] . " " . $user['LastName'];
	$mail_admin->MsgHTML($body_admin);



	/* setup email to user */
	$mail_user = new PHPMailer;

	/* setup email server */
	$mail_user->IsSMTP();
	$mail_user->SMTPAuth = true;
	$mail_user->SMTPSecure = "ssl";
	$mail_user->Host = "box831.bluehost.com";
	$mail_user->Port = 465;
	$mail_user->Username = $email_admin;
	$mail_user->Password = "tkPass775";

	/* setup email */
	$body_user = "Test email. Thanks for your response!";
	$body_user .= "Name " . $user['FirstName'] . " " . $user['LastName'] . " " . $user['Attending'] . "\n";
	$body_user .= "Email " . $user['Email'] . "\n";
	$body_user .= "\n";
	$body_user .= "Name " . $guest['FirstName'] . " " . $guest['LastName'] . " " . $guest['Attending'] . "\n";
	$body_user .= "Email " . $guest['Email'] . "\n";


	$mail_user->setFrom($email_admin, "Tim and Kimberly");
	$mail_user->addAddress($user['Email'], $user['FirstName'] . " " . $user['LastName']); // Add a recipient
	if (isset($guest['Email'])) {
		$mail_user->addCC($guest['Email'], $guest['FirstName'] . " " . $guest['LastName']); // Add a recipient
	}
	$mail_user->Subject = 'Tim and Kimberly Wedding RSVP';
	$mail_user->MsgHTML($body_user);                                 // Set email format to HTML


	/* send emails */
	if(!$mail_admin->send() OR !$mail_user->send()) {
	    return false;
	} else {
		return true;
	}


}




