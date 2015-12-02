<?php

/**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');

class dbUserAction extends coffee{

	function __construct(){
		parent::__construct();

		if($this->what == "changeProfileImage" && !empty($valueToChange)){
			 $this->changeProfileImage();
		}
	}


	private function changeProfileImage(){
		parent::setQuery("UPDATE `usrlist` 
			SET `user_profile_pic`= '".$this->valueToChange."' 
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

	public function new_user(){
		$key = substr( bin2hex(mcrypt_create_iv(25, MCRYPT_DEV_URANDOM)),0, 22);
		
		parent::setQuery("SELECT `id` FROM `usrlist` WHERE `user_name`= '".$this->prams['user_name']."'");
		$user_name = parent::pdoExec();
		
		if($this->prams['money'] == null || is_string($this->prams['money'])){
			$this->prams['money'] = floatval($this->prams['money']);
		}

		parent::setQuery("
			BEGIN;
				INSERT INTO usrlist 
				(user_name, user_profile_pic, coins)
				VALUES
				('".$this->prams['user_name']."', 'default.png', '".$this->prams['money']."');
				INSERT INTO registration_tokens
				(token, user_name, expiration_date) 
				VALUES
				('".$key."', LAST_INSERT_ID(), '".$this->prams['expr_date']."');
			COMMIT;");
		parent::pdoExec();
		echo "<p> The registration token: ".$key."</p>";
	}
}