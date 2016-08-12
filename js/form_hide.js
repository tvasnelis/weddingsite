
function familyCheck() {
    var display = 0;
    var displayDelta = 0;
    if (document.getElementById('ifFour').style.display == 'block') {
        display = 4; 
    } else if (document.getElementById('ifThree').style.display == 'block') {
        display = 3;
    } else if (document.getElementById('ifTwo').style.display == 'block') {
        display = 2; 
    } else if (document.getElementById('ifOne').style.display == 'block') {
        display = 1;
    }

    if (document.getElementById('family_cnt_4').checked) {
        document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'block';
        document.getElementById('ifFour').style.display = 'block';
        displayDelta = 4 - display;
    } else if (document.getElementById('family_cnt_3').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'block';
        document.getElementById('ifFour').style.display = 'none';
        displayDelta = 3 - display;
    } else if (document.getElementById('family_cnt_2').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
        displayDelta = 2 - display;
    } else if (document.getElementById('family_cnt_1').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'none';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
        displayDelta = 1 - display;
    } else {
    	document.getElementById('ifOne').style.display = 'none';
        document.getElementById('ifTwo').style.display = 'none';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
        displayDelta = 0 - display;
    }

    if (displayDelta > 0) {
        i = 0;
        while (i < displayDelta) {
            plusClass();
            i++;
        }
    } else if (displayDelta < 0) {
        i = 0;
        while (i > displayDelta) {
            minusClass();
            i--;
        }
    }
    
}

function setDisplay(guest_cnt) {
	if (guest_cnt == 0) {
		document.getElementById('form_wrap').className = 'default';
	} 
	else if (guest_cnt == 1) {
		document.getElementById('form_wrap').className = 'default';
	} 
	else if (guest_cnt == 2) {
		document.getElementById('form_wrap').className = 'default';
	} 
	else if (guest_cnt == 3) {
		document.getElementById('form_wrap').className = 'state0';
	} 
	else if (guest_cnt == 4) {
		document.getElementById('form_wrap').className = 'state1';
	} 
    else if (guest_cnt == 5) {
		document.getElementById('form_wrap').className = 'state2';
	} 
    else if (guest_cnt == 6) {
        document.getElementById('form_wrap').className = 'state3';
    }
    else if (guest_cnt == 7) {
        document.getElementById('form_wrap').className = 'state4';
    }
    else if (guest_cnt == 8) {
        document.getElementById('form_wrap').className = 'state5';
    }
    else if (guest_cnt == 9) {
        document.getElementById('form_wrap').className = 'state6';
    }
    else if (guest_cnt == 10) {
        document.getElementById('form_wrap').className = 'state7';
    }
    else if (guest_cnt > 10) {
        document.getElementById('form_wrap').className = 'state8';
    }
}

function plusClass() {
    var form = document.getElementById('form_wrap');
    if (form.className == 'default') {
        document.getElementById('form_wrap').className = 'state0';
    } 
    else if (form.className == 'state0') {
        document.getElementById('form_wrap').className = 'state1';
    } 
    else if (form.className == 'state1') {
        document.getElementById('form_wrap').className = 'state2';
    } 
    else if (form.className == 'state2') {
        document.getElementById('form_wrap').className = 'state3';
    } 
    else if (form.className == 'state3') {
        document.getElementById('form_wrap').className = 'state4';
    } 
    else if (form.className == 'state4') {
        document.getElementById('form_wrap').className = 'state5';
    } 
    else if (form.className == 'state5') {
        document.getElementById('form_wrap').className = 'state6';
    } 
    else if (form.className == 'state6') {
        document.getElementById('form_wrap').className = 'state7';
    } 
    else if (form.className == 'state7') {
        document.getElementById('form_wrap').className = 'state8';
    } 
    else if (form.className == 'state8') {
        document.getElementById('form_wrap').className = 'state9';
    } 
    else if (form.className == 'state9') {
        document.getElementById('form_wrap').className = 'state10';
    } 
}

function minusClass() {
    var form = document.getElementById('form_wrap');
    if (form.className == 'state10') {
        document.getElementById('form_wrap').className = 'state9';
    } 
    else if (form.className == 'state9') {
        document.getElementById('form_wrap').className = 'state8';
    } 
    else if (form.className == 'state8') {
        document.getElementById('form_wrap').className = 'state7';
    } 
    else if (form.className == 'state7') {
        document.getElementById('form_wrap').className = 'state6';
    } 
    else if (form.className == 'state6') {
        document.getElementById('form_wrap').className = 'state5';
    } 
    else if (form.className == 'state5') {
        document.getElementById('form_wrap').className = 'state4';
    } 
    else if (form.className == 'state4') {
        document.getElementById('form_wrap').className = 'state3';
    } 
    else if (form.className == 'state3') {
        document.getElementById('form_wrap').className = 'state2';
    } 
    else if (form.className == 'state2') {
        document.getElementById('form_wrap').className = 'state1';
    } 
    else if (form.className == 'state1') {
        document.getElementById('form_wrap').className = 'state0';
    } 
    else if (form.className == 'state0') {
        document.getElementById('form_wrap').className = 'default';
    } 
}

function plusOneCheck() {
    if (document.getElementById('PlusOneAtt').checked) {
        document.getElementById('PlusOne_info').style.display = 'block';
        plusClass();
        plusClass();
    } else {
        document.getElementById('PlusOne_info').style.display = 'none'; 
        minusClass();
        minusClass();
    }
}

window.onload = function() {
  if (document.getElementById('PlusOneAtt').checked) {
        document.getElementById('PlusOne_info').style.display = 'block';
        plusClass();
        plusClass();
    } else {
        document.getElementById('PlusOne_info').style.display = 'none'; 
    }
  familyCheck();
};




