<?php

/**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');

class dbDUserAction extends coffee{
	
	private $what;
	private $twoChange;

	function __construct($what, $twoChange){
		$this->what = $what;
		$this->twoChange = $twoChange;

		if($this->what == "changeProfileImage" && !empty($twoChange)){
			 $this->changeProfileImage();
		}
	}


	private function changeProfileImage(){
		parent::setQuery("UPDATE `usrlist` 
			SET `user_profile_pic`= '".$this->twoChange."' 
			WHERE id IN (
								SELECT person
    							FROM `sessions`
    							WHERE session_id = '".$_SESSION['user']."'
    							);");
		parent::pdoExec();
	}
	private function joinCoffeeSession(){
		//test
	}
}