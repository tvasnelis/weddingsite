<div id="rsvp" class="container-fluid text-center sub-font bg-yellow" href="#rsvp">
  <div class="container">
    <h3 class="section-head">RSVP</h3>
    <?php
    if (!empty($errors)) {
      foreach ($errors as $error) {
        echo "<p class='error sub-font'>" . $error . "</p><br>";
      }
    }
    ?>
    <div id='form_wrap'>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "#rsvp"; ?>" id="rsvp_form">
          <div class="form_header">
            <?php
    				// output for invitation not found
    				if (isset($_GET["status"]) && $_GET["status"] == "invite_notfound") {
    					echo "<p class='form_header'>I can't find you!  Please try again.</p>";
    					echo "<p class='form_header'>Enter your name exactly as it appears on your invitation.</p>";
    				}
            if (empty($guests)) {
              echo "<p class='sub-font form_header'>Enter your name to search for your invitation.</p>";
            } else {
              echo "<p class='sub-font form_header'>Mark each guest below.</p>";
            }
    				?>
          </div>
          <div class="form_body">
            <?php
            if (empty($guests)) {
              // display form for user name (step 1)
            ?>
              <label for="user_firstname" class="">First Name: </label>
              <input type="text" name="user_firstname" id="user_firstname" value="<?php if (isset($user_firstname)) {echo htmlspecialchars($user_firstname);}?>" class="<?php if (isset($errors['user_firstname'])) {echo 'error-border';} ?>" />
              <label for="user_lastname" class="">Last Name: </label>
              <input type="text" name="user_lastname" id="user_lastname" value="<?php if (isset($user_lastname)) {echo htmlspecialchars($user_lastname);}?>" class="<?php if (isset($errors['user_lastname'])) {echo 'error-border';} ?>"/>
              <textarea style="display:none" id="address_1" name="address_1"></textarea>
              <p  style="display:none">Please leave this field blank.</p>
              <input type="submit" name ="submit_1" value="Find Invitation" />
            <?php
            } else {
      				// display rsvp for guests
    					foreach ($guests as $guest) {
    						$guest_firstname = $guest->FirstName;
    						$guest_lastname = $guest->LastName;
    						$guest_fullname = $guest->FirstName . " " . $guest->LastName;
    						$guest_id = $guest->GuestId;
    						$guest_email = $guest->Email;
            ?>
        				<label for="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "Att") ?>" class="name"><?php echo htmlspecialchars($guest_fullname) ?></label>
        				<input type="radio" class="att" id="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "Att") ?>" <?php if ($guest->isAttending()) {echo " checked='checked'";} ?>value="1" name="guests[<?php echo htmlspecialchars($guest_id)?>][attending] ?>" >
        				<label for="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "Att") ?>" class="form_button" >In</label>
        				<input type="radio" class="att" id="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "NotAtt"); ?>" <?php if ($guest->isNotAttending()) {echo " checked='checked'";} ?>value="0" name="guests[<?php echo htmlspecialchars($guest_id)?>][attending] ?>" >
        				<label for="<?php echo htmlspecialchars($guest_firstname . $guest_lastname . "NotAtt") ?>" class="form_button">Out</label><br>
            <?php
              }
              // display rsvp for Plus One
      				if ($user->PlusOne) {
      				?>
        				<label for="PlusOneAtt" class="name">Guest</label>
        				<input type="radio" class="att" id="PlusOneAtt" class="" value="1" name="PlusOne_attending" onchange='javascript:plusOneCheck();' <?php if (isset($_SESSION['temp']['PlusOne']) && $_SESSION['temp']['PlusOne']['attending'] == 1) {echo " checked='checked'";} ?>>
        				<label for="PlusOneAtt" class="form_button">In</label>
        				<input type="radio" class="att" id="PlusOneNotAtt" value="0" name="PlusOne_attending" onchange='javascript:plusOneCheck();' <?php if (isset($_SESSION['temp']['PlusOne']) && $_SESSION['temp']['PlusOne']['attending'] == 0) {echo " checked='checked'";} ?>>
        				<label for="PlusOneNotAtt" class="form_button">Out</label><br>
        				<div id="PlusOne_info" style="display:none">
        	            	<p class='sub-font'>Please enter your guest's name.</p>
        	            	<fieldset class="PlusOne_info">
        		            	<label for="PlusOne_firstname" class="guest_name">First: </label>
        	        			<input type="text" name="PlusOne_firstname" id="PlusOne_firstname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['PlusOne_firstname'])) {echo " error_border";} echo "'"?> value="<?php if (isset($_SESSION['temp']['PlusOne']['firstname'])) {echo $_SESSION['temp']['PlusOne']['firstname'];} ?>" />
        	        			<label for="PlusOne_lastname" class="guest_name">Last: </label>
        	        			<input type="text" name="PlusOne_lastname" id="PlusOne_lastname" class=<?php echo "'guest_name"; if (isset($_SESSION['errors_2']['PlusOne_lastname'])) {echo " error_border";} echo "'"?> value="<?php if (isset($_SESSION['temp']['PlusOne']['lastname'])) {echo $_SESSION['temp']['PlusOne']['lastname'];} ?>" />
        	        		</fieldset>
        	        	</div>
        				<?php
        			}
              // display rsvp for Plus Family
      				if ($user->PlusFamily) {
      					echo "<p class='sub-font'>Your invitation includes your family.</p>";
      					echo "<p class='sub-font'>How many additional guests are attending?</p>";
      					echo "<fieldset class='family_cnt'>";
      					echo "<input id='family_cnt_0' type='radio' class='family_cnt' value='0' name='family_cnt' onchange='javascript:familyCheck();'>";
      					echo "<label for='family_cnt_0' class='family_cnt form_button'>0</label>";
      					echo "<input id='family_cnt_1' type='radio' class='family_cnt' value='1' name='family_cnt' onchange='javascript:familyCheck();'>";
      					echo "<label for='family_cnt_1' class='family_cnt form_button'>1</label>";
      					echo "<input id='family_cnt_2' type='radio' class='family_cnt' value='2' name='family_cnt' onchange='javascript:familyCheck();'>";
      					echo "<label for='family_cnt_2' class='family_cnt form_button'>2</label>";
      					echo "<input id='family_cnt_3' type='radio' class='family_cnt' value='3' name='family_cnt' onchange='javascript:familyCheck();'>";
      					echo "<label for='family_cnt_3' class='family_cnt form_button'>3</label>";
      					echo "<input id='family_cnt_4' type='radio' class='family_cnt' value='4' name='family_cnt' onchange='javascript:familyCheck();'>";
      					echo "<label for='family_cnt_4' class='family_cnt form_button'>4</label>";
      					echo "</fieldset>";
      				?>

      						<div id="ifOne" style="display:none">
      					        <p class='sub-font'>Please enter your guests' names.</p>
      					        <label for="family_1_firstname" class="guest_label">Guest 1</label>
      			            	<label for="family_1_firstname" class="guest_name">First: </label>
      	            			<input type="text" name="family[1][firstname]" id="family_1_firstname" class=<?php echo "'guest_name"; if (isset($errors['family_firstname'][1])) {echo " error_border";} echo "'"?>/>
                          <label for="family_1_lastname" class="guest_name">Last: </label>
      	            			<input type="text" name="family[1][lastname]" id="family_1_lastname" class=<?php echo "'guest_name"; if (isset($errors['errors_2']['family_lastname'][1])) {echo " error_border";} echo "'"?>/>
      					    </div>
                  			<div id="ifTwo" style="display:none">
                  				<label for="family_1_firstname" class="guest_label">Guest 2</label>
      			            	<label for="family_2_firstname" class="guest_name">First: </label>
      	            			<input type="text" name="family[2][firstname]" id="family_2_firstname" class=<?php echo "'guest_name"; if (isset($errors['errors_2']['family_firstname'][2])) {echo " error_border";} echo "'"?>/>
      	            			<label for="family_2_lastname" class="guest_name">Last: </label>
      	            			<input type="text" name="family[2][lastname]" id="family_2_lastname" class=<?php echo "'guest_name"; if (isset($errors['errors_2']['family_lastname'][2])) {echo " error_border";} echo "'"?>/>
      					    </div>
      					    <div id="ifThree" style="display:none">
      					    	<label for="family_1_firstname" class="guest_label">Guest 3</label>
      			            	<label for="family_3_firstname" class="guest_name">First: </label>
      	            			<input type="text" name="family[3][firstname]" id="family_3_firstname" class=<?php echo "'guest_name"; if (isset($errors['errors_2']['family_firstname'][3])) {echo " error_border";} echo "'"?>/>
      	            			<label for="family_1_lastname" class="guest_name">Last: </label>
      	            			<input type="text" name="family[3][lastname]" id="family_3_lastname" class=<?php echo "'guest_name"; if (isset($errors['errors_2']['family_lastname'][3])) {echo " error_border";} echo "'"?>/>
      					    </div>
      					    <div id="ifFour" style="display:none">
      					    	<label for="family_1_firstname" class="guest_label">Guest 4</label>
      			            	<label for="family_4_firstname" class="guest_name">First: </label>
      	            			<input type="text" name="family[4][firstname]" id="family_4_firstname" class=<?php echo "'guest_name"; if (isset($errors['errors_2']['family_firstname'][4])) {echo " error_border";} echo "'"?>/>
      	            			<label for="family_4_lastname" class="guest_name">Last: </label>
      	            			<input type="text" name="family[4][lastname]" id="family_4_lastname" class=<?php echo "'guest_name"; if (isset($errors['errors_2']['family_lastname'][4])) {echo " error_border";} echo "'"?>/>
      					    </div>
      				<?php
      				}
          }
          ?>
            <textarea style="display:none" id="address_2" name="address_2"></textarea>
            <p  style="display:none">Please leave this field blank.</p>
            <input type="submit" name ="submit_2" value="Submit" />

          </div>
        </form>
    </div>
    </div>
</div>

</body>
</html>


<pre> <?php echo "Errors:  "?></pre>
<pre> <?php echo var_dump($errors); ?> </pre>
<pre> <?php echo "Guests:  "?></pre>
<pre> <?php echo var_dump($guests); ?> </pre>
