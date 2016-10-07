<?php

/*
require("guest.php");
$user = new Guest;
$user->FirstName='Tim';
$user->LastName='Vasnelis';
$user->Email='tim.vasnelis@gmail.com';
$user->Attending='1';

$group=array();
$group1=new Guest;
$group1->FirstName='Kimberly';
$group1->LastName='Bean';
$group1->Email='timvkimb@gmail.com';
$group1->Attending='1';

$group=array();
$group2=new Guest;
$group2->FirstName='Penny';
$group2->LastName='Lane';
$group2->Email='plane@timandkimberly.com';
$group2->Attending='1';

$group[]=$group1;
$group[]=$group2;

$guests=array();
$guest1=new Guest;
$guest1->FirstName='John';
$guest1->LastName='Doe';
$guest1->mail='jdoe@timandkimberly.com';
$guest1->Attending='1';

$guest=array();
$guest2=new Guest;
$guest2->FirstName='Jane';
$guest2->LastName='Doe';
$guest2->Email='janedoe@timandkimberly.com';
$guest2->Attending='0';

$guests[]=$guest1;
$guests[]=$guest2;

email_rsvp($user, $group, $guests);
*/

function email_rsvp($guests) {

	require(ROOT_PATH . "inc/phpmailer/PHPMailerAutoload.php");

	$guest_att = 0;
	$guest_cnt = 0;
	foreach ($guests as $guest) {
		$guest_cnt += 1;
		if ($guest->isAttending()) {
			$guest_att += 1;
		}
	}

	$guest_status = '';
	if ($guest_att == $guest_cnt) {
		$guest_status = 'all';
	} elseif ($guest_att == 0) {
		$guest_status = 'none';
	} else {
		$guest_status = 'some';
	}

	$message = "<html><head>
	<style type='text/css'>
    @media screen and (max-width:650px) {
		.wrapper {
			width:90% !important;
		}
	}
	</style>
	<body>
		<table bgcolor='#93c2b2' cellpadding='0' cellspacing='0' border='0' style='width: 98%;'>
			<tr height='20px'></tr>
			<tr><td>
				<table align='center' valign='top' border='0' cellpadding='0' cellspacing='0' bgcolor='#fff'; font-family:Montserrat, Helvetica, sans-serif; style='width:60%; max-width:600px' class='wrapper'>
					<tr><td align='center' colspan=5><a href='www.timandkimberly.com'><img src='../timandkimberly/images/email_header.jpg' alt='Tim & Kimberly' style='max-width:200px;'></a></td></tr>
				</table>
			</td></tr>
			<tr height='20px'></tr>
			<tr><td>
				<table align='center' valign='top' border='0' cellpadding='20px' cellspacing='0' bgcolor='#fff'; font-family:Montserrat, Helvetica, sans-serif; style='width:60%; max-width:600px' class='wrapper'>
					<tr align='center'><td>Success!  We received your RSVP.</td></tr>
				</table>
			</td></tr>
			<tr height='20px'></tr>
			<tr><td>
				<table align='center' valign='top' width='60%' border='0' cellpadding='15px' cellspacing='0' bgcolor='#fff'; font-family:Montserrat, Helvetica, sans-serif; style='width:60%; max-width:600px' class='wrapper'>	
					<tr><td>
						<p>Dear " . $guests[0]->FirstName . ", </p>";
						if ($guest_status == 'all' OR $guest_status == 'some') {
							$message .= "<p>We are so happy you are able to join us in New Orleans!  Please confirm the information below.";				
							$message .= "</p>";
							$message .= "<table align='left' width='50%''><th>Attending</th>";
							foreach ($guests as $guest) {
								if ($guest->isAttending()) {$message .= "<tr align='center'><td>" . $guest->FirstName . " " . $guest->LastName . "</td></tr>";}
							}
							$message .= "</table>";
							if ($guest_status == 'some') {
								$message .= "<table align='left' width='50%''><th>Not Attending</th>";
								foreach ($guests as $guest) {
									if (!$guest->isAttending()) {$message .= "<tr align='center'><td>" . $guest->FirstName . " " . $guest->LastName . "</td></tr>";}
								}
								$message .= "</table>";
							} 
						} else {
							$message .= "<tr><p>We are sorry you are unable to join us in New Orleans.  You will be missed!  If your plans change, please stop back and let us know!</p>";				
						}
					$message .= "</td></tr>";
					$message .= "<tr><td style='padding: 5px 15px;'><p>Once you've made your travel plans, let us know your arrival and departure dates and where you are staying.  We'll keep you up to date on all the details.  We look forward to seeing you there!</p>";
					$message .= "<tr><td style='padding: 5px 15px;'>Sincerely, </td></tr>";
					$message .= "<tr><td style='padding: 5px 15px 15px;'>Tim & Kimberly</td></tr>
				</table>
			</td></tr>
			<tr height='20px'></tr>
		</table>

		";

	//echo $message;

	// define admin email address
	$email_admin = "rsvp@timandkimberly.com";

	// setup email to user 
	$mail_admin = new PHPMailer;

	// verify email address 
	if (!$mail_admin->ValidateAddress($guests[0]->Email)) {
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
	$body_admin = "The following user has provided an RSVP via timandkimberly.com<br/>";
	$body_admin .= "Email:" . $guests[0]->Email . "<br/>";
	foreach ($guests as $guest) {
		$body_admin .= "Name: " . $guest->FirstName . " " . $guest->LastName;
		if ($guest->Attending == 1) {
			$body_admin .= " Attending<br/>";
		} else {
			$body_admin .= " Not Attending<br/>";
		}
	}
	
	$mail_admin->setFrom($email_admin, "Tim and Kimberly");
	$mail_admin->addAddress($email_admin, "Tim and Kimberly"); // Add a recipient
	$mail_admin->Subject = 'Wedding RSVP from ' . $guests[0]->FirstName . " " . $guests[0]->LastName;
	$mail_admin->MsgHTML($body_admin);

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

	$mail_user->setFrom($email_admin, "Tim and Kimberly");
	$mail_user->addAddress($guests[0]->Email, $guests[0]->FirstName . " " . $guests[0]->LastName); // Add a recipient
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
}

