window.onload = function() {
  familyCheck();
  plusOneCheck();
};
function familyCheck() {
    if (document.getElementById('family_cnt_4').checked) {
        document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'block';
        document.getElementById('ifFour').style.display = 'block';
        document.getElementById("form_wrap").className = '';
        document.getElementById("form_wrap").className = 'state4';
    } else if (document.getElementById('family_cnt_3').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'block';
        document.getElementById('ifFour').style.display = 'none';
        document.getElementById("form_wrap").className = '';
        document.getElementById("form_wrap").className = 'state3';
    } else if (document.getElementById('family_cnt_2').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'block';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
        document.getElementById("form_wrap").className = '';
        document.getElementById("form_wrap").className = 'state2';
    } else if (document.getElementById('family_cnt_1').checked){
    	document.getElementById('ifOne').style.display = 'block';
        document.getElementById('ifTwo').style.display = 'none';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
        document.getElementById("form_wrap").className = '';
        document.getElementById("form_wrap").className = 'state1';
    } else {
    	document.getElementById('ifOne').style.display = 'none';
        document.getElementById('ifTwo').style.display = 'none';
        document.getElementById('ifThree').style.display = 'none';
        document.getElementById('ifFour').style.display = 'none';
        document.getElementById("form_wrap").className = '';
        document.getElementById("form_wrap").className = 'state0';
    }
}

function plusOneCheck() {
    if (document.getElementById('PlusOneAtt').checked) {
        document.getElementById('PlusOne_info').style.display = 'block';
        document.getElementById("form_wrap").className = '';
        document.getElementById("form_wrap").className = 'state0';
    } else {
    	document.getElementById('PlusOne_info').style.display = 'none';
    	document.getElementById("form_wrap").className = '';
        document.getElementById("form_wrap").className = 'default';
    }
}

function plusClass(guest_cnt) {
	if (guest_cnt == 0) {
		document.getElementById('form_wrap').className = 'default';
	} 
	else if (guest_cnt == 1) {
		document.getElementById('form_wrap').className = 'default';
	} 
	else if (guest_cnt == 2) {
		document.getElementById('form_wrap').className = 'state0';
	} 
	else if (guest_cnt == 3) {
		document.getElementById('form_wrap').className = 'state0';
	} 
	else if (guest_cnt == 4) {
		document.getElementById('form_wrap').className = 'state1';
	} 
	else if (guest_cnt > 4) {
		document.getElementById('form_wrap').className = 'state1';
	} 
}



