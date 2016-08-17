<?php 

$pageTitle = "Tim & Kimberly - RSVP";
$section = "rsvp";

require_once("inc/config.php");
require(ROOT_PATH . "inc/database.php");
require(ROOT_PATH . "inc/functions.php");
require(ROOT_PATH . "inc/guest.php");

// intialize error varaible
$errors = array();

// read POST form data for form step m1
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_1'])) {

  // validate form data and read values
  if (empty($_POST['user_firstname'])) {
      $errors["user_firstname"] = true;
    } else {
      // set session first name after trim and proper case
      $user_firstname = (ucwords(strtolower(trim(filter_input(INPUT_POST, "user_firstname", FILTER_SANITIZE_STRING)))));
    }

    if (empty($_POST['user_lastname'])) {
        $errors["user_lastname"] = true;
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
      $user_query = $db->prepare("SELECT GuestId, FirstName, LastName, GroupId, Email, PlusOne, PlusFamily FROM Guests WHERE FirstName = :FirstName AND LastName = :LastName");
      $user_query->bindValue(':FirstName',$user_firstname, PDO::PARAM_STR);
      $user_query->bindValue(':LastName',$user_lastname, PDO::PARAM_STR);
      echo var_dump($user_firstname);
      echo var_dump($user_query);
      $user_query->execute();
    } catch (Exception $e) {
      echo "<p class='error sub-font'>Database connection error. Please try again later.</p>";
      exit;
    } 
    $user_tblGuests = $user_query->fetchAll(PDO::FETCH_ASSOC);

    echo var_dump($user_tblGuests);
  }

}

?>



<div id="rsvp" class="container-fluid text-center sub-font bg-yellow" href="#rsvp">
  <div class="container">
    <h3 class="section-head">RSVP</h3>
    <div id='form_wrap'>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "#rsvp"; ?>" id="rsvp_form">

          <div class="form_header">
          </div>  

          <div class="form_body">
              <label for="user_firstname" class="">First Name: </label>
              <input type="text" name="user_firstname" id="user_firstname" value="<?php if (isset($user_firstname)) {echo htmlspecialchars($user_firstname);}?>" class="<?php if (isset($errors['user_firstname'])) {echo 'error-border';} ?>" />
              <label for="user_lastname" class="">Last Name: </label>
              <input type="text" name="user_lastname" id="user_lastname" value="<?php if (isset($user_lastname)) {echo htmlspecialchars($user_lastname);}?>" class="<?php if (isset($errors['user_lastname'])) {echo 'error-border';} ?>"/>
              <textarea style="display:none" id="address_1" name="address_1"></textarea>
              <p  style="display:none">Please leave this field blank.</p>
              <input type="submit" name ="submit_1" value="Find Invitation" />
          </div>    
        </form>
    </div>
    </div>
</div>

</body>
</html>

<?php 
echo var_dump($errors);
?>