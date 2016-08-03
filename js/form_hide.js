window.onload = function() {
  familyCheck();
  plusOneCheck();
};


function plusClass() {
    if (document.getElementById('form_wrap').className == 'default') {
        document.getElementById('form_wrap').className = 'state0';
    } 
    else if (document.getElementById('form_wrap').className == 'state0') {
        document.getElementById('form_wrap').className = 'state1';
    } 
    else if (document.getElementById('form_wrap').className == 'state1') {
        document.getElementById('form_wrap').className = 'state2';
    } 
    else if (document.getElementById('form_wrap').className == 'state2') {
        document.getElementById('form_wrap').className = 'state3';
    } 
    else if (document.getElementById('form_wrap').className == 'state3') {
        document.getElementById('form_wrap').className = 'state4';
    } 
    else if (document.getElementById('form_wrap').className == 'state4') {
        document.getElementById('form_wrap').className = 'state5';
    } 
    else if (document.getElementById('form_wrap').className == 'state5') {
        document.getElementById('form_wrap').className = 'state6';
    } 
    else if (document.getElementById('form_wrap').className == 'state6') {
        document.getElementById('form_wrap').className = 'state7';
    } 
    else if (document.getElementById('form_wrap').className == 'state8') {
        document.getElementById('form_wrap').className = 'state9';
    } 
    else if (document.getElementById('form_wrap').className == 'state0') {
        document.getElementById('form_wrap').className = 'state10';
    } 

}

function familyCheck() {
    if (document.getElementById('family_cnt_4').checked) {
        document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'block';
        document.getElementById('ifFour').style.display = 'block';
    } else if (document.getElementById('family_cnt_3').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'block';
        document.getElementById('ifFour').style.display = 'none';
    } else if (document.getElementById('family_cnt_2').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
    } else if (document.getElementById('family_cnt_1').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'none';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
    } else {
    	document.getElementById('ifOne').style.display = 'none';
        document.getElementById('ifTwo').style.display = 'none';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
    }
}

function plusOneCheck() {
    if (document.getElementById('PlusOneAtt').checked) {
        document.getElementById('PlusOne_info').style.display = 'block';
        if (document.getElementById('form_wrap').className == 'default') {
            document.getElementById('form_wrap').className = 'state1';
        } 
        else if (document.getElementById('form_wrap').className == 'state0') {
            document.getElementById('form_wrap').className = 'state2';
        } 
        else if (document.getElementById('form_wrap').className == 'state1') {
            document.getElementById('form_wrap').className = 'state3';
        } 
        else if (document.getElementById('form_wrap').className == 'state1') {
            document.getElementById('form_wrap').className = 'state3';
        } 
        else if (document.getElementById('form_wrap').className == 'state2') {
            document.getElementById('form_wrap').className = 'state4';
        } 
        else if (document.getElementById('form_wrap').className == 'state3') {
            document.getElementById('form_wrap').className = 'state5';
        } 
        else if (document.getElementById('form_wrap').className == 'state4') {
            document.getElementById('form_wrap').className = 'state6';
        } 
        else if (document.getElementById('form_wrap').className == 'state5') {
            document.getElementById('form_wrap').className = 'state7';
        } 
        else if (document.getElementById('form_wrap').className == 'state6') {
            document.getElementById('form_wrap').className = 'state8';
        } 
        else if (document.getElementById('form_wrap').className == 'state7') {
            document.getElementById('form_wrap').className = 'state9';
        } 
        else if (document.getElementById('form_wrap').className == 'state8') {
            document.getElementById('form_wrap').className = 'state10';
        } 
        else if (document.getElementById('form_wrap').className == 'state9') {
            document.getElementById('form_wrap').className = 'state10';
        } 

    } else {
    	document.getElementById('PlusOne_info').style.display = 'none';
        if (document.getElementById('form_wrap').className == 'state10') {
            document.getElementById('form_wrap').className = 'state8';
        } 
        else if (document.getElementById('form_wrap').className == 'state9') {
            document.getElementById('form_wrap').className = 'state7';
        } 
        else if (document.getElementById('form_wrap').className == 'state8') {
            document.getElementById('form_wrap').className = 'state9';
        } 
        else if (document.getElementById('form_wrap').className == 'state7') {
            document.getElementById('form_wrap').className = 'state5';
        } 
        else if (document.getElementById('form_wrap').className == 'state6') {
            document.getElementById('form_wrap').className = 'state4';
        } 
        else if (document.getElementById('form_wrap').className == 'state5') {
            document.getElementById('form_wrap').className = 'state3';
        } 
        else if (document.getElementById('form_wrap').className == 'state4') {
            document.getElementById('form_wrap').className = 'state2';
        } 
        else if (document.getElementById('form_wrap').className == 'state3') {
            document.getElementById('form_wrap').className = 'state1';
        } 
        else if (document.getElementById('form_wrap').className == 'state2') {
            document.getElementById('form_wrap').className = 'state0';
        } 
        else if (document.getElementById('form_wrap').className == 'state1') {
            document.getElementById('form_wrap').className = 'default';
        } 
        else if (document.getElementById('form_wrap').className == 'state0') {
            document.getElementById('form_wrap').className = 'default';
        } 
    }
}

function guestClass(guest_cnt) {
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
    if (document.getElementById('form_wrap').className == 'default') {
        document.getElementById('form_wrap').className = 'state0';
    } 
    else if (document.getElementById('form_wrap').className == 'state0') {
        document.getElementById('form_wrap').className = 'state1';
    } 
    else if (document.getElementById('form_wrap').className == 'state1') {
        document.getElementById('form_wrap').className = 'state2';
    } 
    else if (document.getElementById('form_wrap').className == 'state2') {
        document.getElementById('form_wrap').className = 'state3';
    } 
    else if (document.getElementById('form_wrap').className == 'state3') {
        document.getElementById('form_wrap').className = 'state4';
    } 
    else if (document.getElementById('form_wrap').className == 'state4') {
        document.getElementById('form_wrap').className = 'state5';
    } 
    else if (document.getElementById('form_wrap').className == 'state5') {
        document.getElementById('form_wrap').className = 'state6';
    } 
    else if (document.getElementById('form_wrap').className == 'state6') {
        document.getElementById('form_wrap').className = 'state7';
    } 
    else if (document.getElementById('form_wrap').className == 'state7') {
        document.getElementById('form_wrap').className = 'state8';
    } 
    else if (document.getElementById('form_wrap').className == 'state8') {
        document.getElementById('form_wrap').className = 'state9';
    } 
    else if (document.getElementById('form_wrap').className == 'state9') {
        document.getElementById('form_wrap').className = 'state10';
    } 

}



