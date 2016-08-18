
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
    } else {
        document.getElementById('PlusOne_info').style.display = 'none';
    }
}

window.onload = function() {
  plusOneCheck();
  familyCheck();
};
