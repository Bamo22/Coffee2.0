<?php
/**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');

class dbAUserAction extends coffee{
	
	private $what;

	function __construct($what){
		$this->what = $what;
		if($this->what == 'new_user'){
			return $this->new_user();
		}
	}

	private function new_coffee_session(){

	}
	private function new_user(){
		$key = substr( bin2hex(mcrypt_create_iv(25, MCRYPT_DEV_URANDOM)),0, 22);
		$username = parent::$this->prams['user_name'];
		echo parent::$this->prams['user_name'];
		parent::setQuery("SELECT `id` FROM `usrlist` WHERE `user_name` = '".$username."';");
		$user_name = parent::pdoExec();
		if($user_name[0]['id'] == NULL){return "This username already exists"; exit;} else{
			var_dump($user_name);
		}
		if(parent::$this->prams['money'] == null || is_string(parent::$prams['money'])){
			parent::$this->prams['money'] = floatval(parent::$this->prams['money']);
		}

		parent::setQuery("
			BEGIN;
				INSERT INTO usrlist 
				(user_name, user_profile_pic, coins)
				VALUES
				('".parent::$this->prams['user_name']."', 'default.png', '".parent::$this->prams['money']."');
				INSERT INTO registration_tokens
				(token, user_name, expiration_date) 
				VALUES
				('".$key."', LAST_INSERT_ID(), '".parent::$this->prams['expr_date']."');
			COMMIT;");
		parent::pdoExec();
		return "<p> The registration token:".$key."</p>";
	}
}