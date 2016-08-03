<?php

require("../inc/guest.php");
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


function email_rsvp($user, $group, $guests) {

	//require_once("inc/phpmailer/PHPMailerAutoload.php");



$message = "<html><body>
	<table width='98%' bgcolor='#93c2b2' cellpadding='0' cellspacing='0' border='0'>
		<tr>
			<td>
				<table align='center' valign='top' width='60%' max-width='600px' border='0' cellpadding='0' cellspacing='0' bgcolor='#fff'; font-family:Montserrat, Helvetica, sans-serif;>
					<tr><td align='center' colspan=5><a href='/timandkimberly'><img src='../images/email_header.jpg'></a></td></tr>
					<!--<tr height='30px' align='center' valign='bottom' >
						<td style='width:20%'><a href='../wedding' target='_top' style='text-decoration:none; color:black'>Wedding</a></td>
						<td style='width:20%'><a href='../travel' target='_top' style='text-decoration:none; color:black'>Travel</a></td>
						<td style='width:20%'><a href='../stay' target='_top' style='text-decoration:none; color:black'>Stay</a></td>
						<td style='width:20%'><a href='../experience' target='_top' style='text-decoration:none; color:black'>Experience</a></td>
						<td style='width:20%'><a href='../RSVP' target='_top' style='text-decoration:none; color:black'>RSVP</a></td>
					</tr>-->
				</table>
			</td>
		</tr>
		<tr height='20px'></tr>
		<tr>
			<td>
				<table align='center' valign='top' width='60%' max-width='600px' border='0' cellpadding='20px' cellspacing='0' bgcolor='#fff'; font-family:Montserrat, Helvetica, sans-serif;>
					<tr> 
						<td>Success!</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>







	";


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

