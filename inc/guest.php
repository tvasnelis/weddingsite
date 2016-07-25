<?php
class Guest	{

	/* Guest variables */
	var $GuestId;
	var $FirstName;
	var $LastName;
	var $GroupId;
	var $Email;
	var $PlusOne;
	var $PlusFamily;
	var $Attending;
	var $SubDate;
	Var $RsvpUser;


	/* Member functions */
	function setGuestId($par){
	 $this->GuestId = $par;
	}

	function getGuestId(){
	 echo $this->GuestId;
	}

	function setFirstName($par){
	 $this->FirstName = $par;
	}


	function getFirstName(){
	 echo $this->FirstName;
	}

	function issetFirstName(){
	 if ($this->FirstName == "") {
	 	return false;
	 } else {
	 	return true;
	 }
	}

	function setLastName($par){
	 $this->LastName = $par;
	}

	function getLastName(){
	 echo $this->LastName;
	}

	function issetLastName(){
	 if ($this->LastName == "") {
	 	return false;
	 } else {
	 	return true;
	 }
	}

	function setGroupId($par){
	 $this->GroupId = $par;
	}

	function getGroupId(){
	 echo $this->GroupId;
	}

	function setAttending($par){
	 $this->Attending = $par;
	}

	function getAttending(){
	 echo $this->Attending;
	}

	function setEmail($par){
	 $this->Email = $par;
	}

	function getEmail(){
	 echo $this->Email;
	}

	function setPlusOne($par){
	 $this->PlusOne = $par;
	}

	function getPlusOne(){
	 echo $this->PlusOne;
	}

	function setPlusFamily($par){
	 $this->PlusFamily = $par;
	}

	function getPlusFamily(){
	 echo $this->PlusFamily;
	}

	function setProp($prop, $par){
	 $this->$prop = $par;
	}

	function setSubDate($par){
	 $this->SubDate = $par;
	}

	function setRsvpUser($par){
	 $this->RsvpUser = $par;
	}


}
?>