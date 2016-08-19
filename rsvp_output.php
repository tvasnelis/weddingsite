<div id="rsvp" class="container-fluid text-center sub-font bg-yellow" href="#rsvp">
  <h3 class="section-head">RSVP</h3>
  <?php
  // display error messages
  if (!empty($errors)) {
    if (isset($errors['user_firstname']) || isset($errors['user_lastname'])) {
      echo "<p class='error sub-font'>" . $errorMessage_name . "</p>";  
    }
    if (isset($errors['database'])) {
      echo "<p class='error sub-font'>" . $errorMessage_databaseconnection . "</p>";  
    }
    if (isset($errors['PlusOne_firstname']) || isset($errors['PlusOne_lastname']) || isset($errors['family_firstname']) || isset($errors['family_lastname'])) {
      echo "<p class='error sub-font'>" . $errorMessage_guestname . "</p>";  
    }
    if (isset($errors['guest_attending'])) {
      echo "<p class='error sub-font'>" . $errorMessage_guestatt . "</p>";  
    }
    if (isset($errors['user_email'])) {
      echo "<p class='error sub-font'>" . $errorMessage_email . "</p>";  
    }
  }
  ?>
  <div id='form_wrap'>
      <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "#rsvp"; ?>" id="rsvp_form">
        <div class="form_header">
          <?php
  				// output form header 
          if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
            echo "<p class='form_header'>Thanks for your response!</p>";
          } else {
    				if (isset($_GET["status"]) && $_GET["status"] == "invite_notfound") {
    					echo "<p class='form_header'>I can't find you!  Please try again.</p>";
    					echo "<p class='form_header'>Enter your name exactly as it appears on your invitation.</p>";
    				}
            if (empty($_SESSION["guests"])) {
              echo "<p class='sub-font form_header'>Enter your name to search for your invitation.</p>";
            } else {
              echo "<p class='sub-font form_header'>Your invitation includes the following guests. Who's in!?</p>";
            }
          
  				?>
        </div>
          <div class="form_body">
            <input type="hidden" name="formid" value="<?php echo $_SESSION["formid"]; ?>" />
            <?php if (empty($_SESSION["guests"])) {
              // display form for user name (step 1) 
            ?>
              <div class="form_width">
                <label for="user_firstname" class="name">First: </label>
                <input type="text" name="user_firstname" id="user_firstname" value="<?php if (isset($user_firstname)) {echo htmlspecialchars($user_firstname);}?>" class="<?php if (isset($errors['user_firstname'])) {echo 'error-border ';} echo "name" ?>" />
              </div>
              <div class="form_width">
                <label for="user_lastname" class="name">Last: </label>
                <input type="text" name="user_lastname" id="user_lastname" value="<?php if (isset($user_lastname)) {echo htmlspecialchars($user_lastname);}?>" class="<?php if (isset($errors['user_lastname'])) {echo 'error-border ';} echo "name" ?>"/>
               </div>
              <textarea style="display:none" id="address_1" name="address_1"></textarea>
              <p  style="display:none">Please leave this field blank.</p>
              <input type="submit" id="submit_1" name ="submit_1" value="Find Invitation" />
            <?php } else {
              // display form for RSVP (step 2)
      			  // display rsvp for guests
    					foreach ($_SESSION["guests"] as $guest) {
    						$guest_firstname = $guest->FirstName;
    						$guest_lastname = $guest->LastName;
    						$guest_fullname = $guest->FirstName . " " . $guest->LastName;
    						$guest_id = $guest->GuestId;
    						$guest_email = $guest->Email;
            ?>
        				<label for="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "Att") ?>" class="guest_name"><?php echo htmlspecialchars($guest_fullname) ?></label>
        				<input type="radio" class="att" id="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "Att") ?>" <?php if ($guest->isAttending()) {echo " checked='checked'";} ?>value="1" name="guests[<?php echo htmlspecialchars($guest_id)?>][attending] ?>" >
        				<label for="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "Att") ?>" class="form_button" >In</label>
        				<input type="radio" class="att" id="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "NotAtt"); ?>" <?php if ($guest->isNotAttending()) {echo " checked='checked'";} ?>value="0" name="guests[<?php echo htmlspecialchars($guest_id)?>][attending] ?>" >
        				<label for="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "NotAtt") ?>" class="form_button">Out</label><br>
            <?php }
              // display rsvp for Plus One
      				if ($_SESSION["guests"][0]->PlusOne) {
      			?>
        				<label for="PlusOneAtt" class="guest_name">Guest</label>
        				<input type="radio" class="att" id="PlusOneAtt" class="" value="1" name="PlusOne_attending" onchange='javascript:plusOneCheck();' <?php if (isset($PlusOne['attending']) && $PlusOne['attending'] == 1) {echo " checked='checked'";} ?>>
        				<label for="PlusOneAtt" class="form_button">In</label>
        				<input type="radio" class="att" id="PlusOneNotAtt" value="0" name="PlusOne_attending" onchange='javascript:plusOneCheck();' <?php if (isset($PlusOne['attending']) && $PlusOne['attending'] == 0) {echo " checked='checked'";} ?>>
        				<label for="PlusOneNotAtt" class="form_button">Out</label><br>
        				<div id="PlusOne_info" style="display:none">
                  <p class='sub-font header'>Please enter your guest's name.</p>
                  <div class="form_width">
                    <label for="PlusOne_firstname" class="name">First: </label>
                    <input type="text" name="PlusOne_firstname" id="PlusOne_firstname" class=<?php echo "'name"; if (isset($errors['PlusOne_firstname'])) {echo " error-border";} echo "'"?> value="<?php if (isset($PlusOne['firstname'])) {echo htmlspecialchars($PlusOne['firstname']);} ?>" />
                  </div>
                  <div class="form_width">  
                    <label for="PlusOne_lastname" class="name">Last: </label>
                    <input type="text" name="PlusOne_lastname" id="PlusOne_lastname" class=<?php echo "'name"; if (isset($errors['PlusOne_lastname'])) {echo " error-border";} echo "'"?> value="<?php if (isset($PlusOne['lastname'])) {echo htmlspecialchars($PlusOne['lastname']);} ?>" />
        	         </div>
        	        </div>
        			<?php
        			}
              // display rsvp for Plus Family
      				if ($_SESSION["guests"][0]->PlusFamily) {
      					echo "<p class='sub-font header'>Your invitation includes your family.</p>";
      					echo "<p class='sub-font'>How many additional guests are attending?</p>";
      					echo "<input id='family_cnt_0' type='radio' class='family_cnt' value='0' name='family_cnt' onchange='javascript:familyCheck();'";
                  if (!isset($family_cnt) || $family_cnt == 0) {echo " checked='checked'";}
                  echo ">";
      					echo "<label for='family_cnt_0' class='family_cnt form_button'>0</label>";
                echo "<input id='family_cnt_1' type='radio' class='family_cnt' value='1' name='family_cnt' onchange='javascript:familyCheck();'";
      				    if (isset($family_cnt) && $family_cnt == 1) {echo " checked='checked'";}
                  echo ">";
                echo "<label for='family_cnt_1' class='family_cnt form_button'>1</label>";
      					echo "<input id='family_cnt_2' type='radio' class='family_cnt' value='2' name='family_cnt' onchange='javascript:familyCheck();'";
      				    if (isset($family_cnt) && $family_cnt == 2) {echo " checked='checked'";}
                  echo ">";
                echo "<label for='family_cnt_2' class='family_cnt form_button'>2</label>";
      					echo "<input id='family_cnt_3' type='radio' class='family_cnt' value='3' name='family_cnt' onchange='javascript:familyCheck();'";
                  if (isset($family_cnt) && $family_cnt == 3) {echo " checked='checked'";}
                  echo ">";
                echo "<label for='family_cnt_3' class='family_cnt form_button'>3</label>";
      					echo "<input id='family_cnt_4' type='radio' class='family_cnt' value='4' name='family_cnt' onchange='javascript:familyCheck();'";
      				    if (isset($family_cnt) && $family_cnt == 4) {echo " checked='checked'";}
                  echo ">";
                echo "<label for='family_cnt_4' class='family_cnt form_button'>4</label>";
      					
      				?>

      					<div id="ifOne" style="display:none">
                  <p class='sub-font header'>Please enter your guests' names.</p>
      					  <fieldset class="family_guest">
                      <label for="family_1_firstname" class="guest_label">Guest 1</label>
                    <div class="form_width">  
                      <label for="family_1_firstname" class="name">First: </label>
                      <input type="text" name="family[1][firstname]" id="family_1_firstname" class=<?php echo "'name"; if (isset($errors['family_firstname'][1])) {echo " error-border";} echo "'"?>
                        value="<?php if (isset($family[1]['firstname'])) {echo htmlspecialchars($family[1]['firstname']);} ?>" />
                    </div>
                    <div class="form_width"> 
                      <label for="family_1_lastname" class="name">Last: </label>
                      <input type="text" name="family[1][lastname]" id="family_1_lastname" class=<?php echo "'name"; if (isset($errors['family_lastname'][1])) {echo " error-border";} echo "'"?>
                        value="<?php if (isset($family[1]['lastname'])) {echo htmlspecialchars($family[1]['lastname']);} ?>" />
                    </div>
                  </fieldset>
                </div>
                <div id="ifTwo" style="display:none">
                  <fieldset class="family_guest">
                    <label for="family_2_firstname" class="guest_label">Guest 2</label>
                    <div class="form_width">
                      <label for="family_2_firstname" class="name">First: </label>
                      <input type="text" name="family[2][firstname]" id="family_2_firstname" class=<?php echo "'name"; if (isset($errors['family_firstname'][2])) {echo " error-border";} echo "'"?>
                        value="<?php if (isset($family[2]['firstname'])) {echo htmlspecialchars($family[2]['firstname']);} ?>" />
                    </div>
                    <div class="form_width">    
                      <label for="family_2_lastname" class="name">Last: </label>
                      <input type="text" name="family[2][lastname]" id="family_2_lastname" class=<?php echo "'name"; if (isset($errors['family_lastname'][2])) {echo " error-border";} echo "'"?>
                        value="<?php if (isset($family[2]['lastname'])) {echo htmlspecialchars($family[2]['lastname']);} ?>" />
                    </div>
                  </fieldset>
                </div>
                <div id="ifThree" style="display:none">
                  <fieldset class="family_guest">
                    <label for="family_3_firstname" class="guest_label">Guest 3</label>
                    <div class="form_width">     
                      <label for="family_3_firstname" class="name">First: </label>
                      <input type="text" name="family[3][firstname]" id="family_3_firstname" class=<?php echo "'name"; if (isset($errors['family_firstname'][3])) {echo " error-border";} echo "'"?>
                        value="<?php if (isset($family[3]['firstname'])) {echo htmlspecialchars($family[3]['firstname']);} ?>" />
                    </div>
                    <div class="form_width">     
                      <label for="family_3_lastname" class="name">Last: </label>
                      <input type="text" name="family[3][lastname]" id="family_3_lastname" class=<?php echo "'name"; if (isset($errors['family_lastname'][3])) {echo " error-border";} echo "'"?>
                        value="<?php if (isset($family[3]['lastname'])) {echo htmlspecialchars($family[3]['lastname']);} ?>" />
                    </div>
                  </fieldset>    
                </div>
                <div id="ifFour" style="display:none">
                  <fieldset class="family_guest">
                      <label for="family_4_firstname" class="guest_label">Guest 4</label>
                     <div class="form_width">   
                      <label for="family_4_firstname" class="name">First: </label>
                      <input type="text" name="family[4][firstname]" id="family_4_firstname" class=<?php echo "'name"; if (isset($errors['family_firstname'][4])) {echo " error-border";} echo "'"?>
                        value="<?php if (isset($family[4]['firstname'])) {echo htmlspecialchars($family[4]['firstname']);} ?>" />
                    </div>
                    <div class="form_width">    
                      <label for="family_4_lastname" class="name">Last: </label>
                      <input type="text" name="family[4][lastname]" id="family_4_lastname" class=<?php echo "'name"; if (isset($errors['family_lastname'][4])) {echo " error-border";} echo "'"?>
                        value="<?php if (isset($family[4]['lastname'])) {echo htmlspecialchars($family[4]['lastname']);} ?>" />
                    </div>
                  </fieldset>
                </div>


      				<?php } ?>
              <p class='sub-font header'>Please provide an email address for confirmation.</p>
              <label for="user_email" class="email">Email: </label>
              <input type="text" name="user_email" id="user_email" value="<?php htmlspecialchars($_SESSION['guests'][0]->getEmail());?>" class=<?php echo "'email"; if (isset($errors['user_email'])) {echo " error-border";} echo "'"?>><br>
              <textarea style="display:none" id="address_2" name="address_2"></textarea>
              <p  style="display:none">Please leave this field blank.</p>
              <input type="submit" id="submit_2" name ="submit_2" value="Submit" />
            <?php
      			} 
          }
            ?>
          </div>
         
      </form>
  </div>
</div>

</body>
</html>
