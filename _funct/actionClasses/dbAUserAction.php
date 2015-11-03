<?php
/**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');

class dbAUserAction extends coffee{
	
	private $what;
	private $twoChange;

	function __construct($what, $twoChange){
		$this->what = $what;
		$this->twoChange = $twoChange;
	}

	private function new_coffee_session(){

	}
	private function new_user(){
		$key = substr( bin2hex(mcrypt_create_iv(25, MCRYPT_DEV_URANDOM)),0, 22);
		if($this->twoChange['money'] == null || is_string($this->twoChange['money'])){
			$this->twoChange['money'] = floatval($this->twoChange['money']);
		}

		parent::setQuery("
			BEGIN;
			INSERT INTO usrlist 
			(user_name, user_profile_pic, coins)
			VALUES
			('".$this->twoChange['name']."', 'default.png', '".$this->twoChange['money']."');
			INSERT INTO registration_tokens
			(token, user_name, expiration_date) 
			VALUES
			('".$key."', LAST_INSERT_ID(), '".$this->twoChange['expr_date']."');
			COMMIT;");
		parent::pdoExec();

	}
}