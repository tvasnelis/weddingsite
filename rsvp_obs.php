<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$first_name = trim(filter_input(INPUT_POST, "first_name", FILTER_SANITIZE_STRING));
	$last_name = trim(filter_input(INPUT_POST, "last_name", FILTER_SANITIZE_STRING));
	$email_user = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
	$email_admin = "rsvp@timandkimberly.com";

	if ($first_name == "" || $last_name == "" || $email_user == "") {
		echo "Please fill in the required fields.";
		exit;
	}
	if ($_POST["address"] != "") {
		echo "Bad form input";
		exit;
	}


	/* Send emails */
	require("inc/phpmailer/PHPMailerAutoload.php");

	/* setup email to RSVP admin */
	$mail_admin = new PHPMailer;

	/* verify email address */
	if (!$mail_admin->ValidateAddress($email_admin)) {
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
	/* $mail_admin->SMTPOptions = array(
	    'ssl' => array(
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	        'allow_self_signed' => true
	    )
	); */

	/* setup email */
	$body_admin = "Test email. The following user has provided and RSVP via timandkimberly.com";
	$body_admin .= "Name " . $first_name . " " . $last_name . "\n";
	$body_admin .= "Email " . $email_user . "\n";

	$mail_admin->setFrom($email_admin, "Tim and Kimberly");
	$mail_admin->addAddress($email_admin, "Tim and Kimberly"); // Add a recipient
	$mail_admin->Subject = 'Wedding RSVP from ' . $first_name . " " . $last_name;
	$mail_admin->MsgHTML($body_admin);


	/* setup email to user */
	$mail_user = new PHPMailer;

	/* verify email address */
	if (!$mail_user->ValidateAddress($email_user)) {
		echo "invalid email address.";
		exit;
	}

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
	$body_user .= "Name " . $first_name . " " . $last_name . "\n";
	$body_user .= "Email " . $email_user . "\n";

	$mail_user->setFrom($email_admin, "Tim and Kimberly");
	$mail_user->addAddress($email_user, $first_name . " " . $last_name); // Add a recipient
	$mail_user->Subject = 'Tim and Kimberly Wedding RSVP';
	$mail_user->MsgHTML($body_user);                                 // Set email format to HTML


	/* send emails */
	if(!$mail_admin->send() OR !$mail_user->send()) {
		echo 'Message could not be sent.' . '</br>';
	    echo 'Mailer Error Admin: ' . $mail_admin->ErrorInfo . '</br>';
	    echo 'Mailer Error User: ' . $mail_user->ErrorInfo;
	    exit();
	} else {
		header("location:rsvp.php?status=thanks");
		exit();
	}
}


$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

include("inc/header.php");
include("inc/guest_list.php"); ?>

<link rel="stylesheet" type="text/css" href="css/rsvp.css">

    <div id="wrap" class="sub-font">
	<?php if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
	echo "<p class='sub-font'>RSVP form under construction.  Check back soon!</p>";
	echo "<div id='form_wrap'>";
	echo "<style>";
	echo "#form_wrap {display: none;}";
	echo "</style>";
	} else { ?>
    <div id='form_wrap' class="sub-font">
        <form method="POST" action="rsvp.php">
           	<label for="first_name">First Name: </label>
            <input type="text" name="first_name" value="" id="first_name" />
            <label for="last_name">Last Name: </label>
            <input type="text" name="last_name" value="" id="last_name" />
            <label for="email">Email Address: </label>
            <input type="text" name="email" value="" id="email" />
            <label style="display:none" for="address">Address</label>
			<textarea style="display:none" id="address" name="address"></textarea>
			<p  style="display:none">Please leave this field blank.</p>
            <input type="submit" name ="submit" value="Find Invitation" />
			</tr>
        </form>
	<?php } ?>
		</div>
    </div>

</body>
</html>