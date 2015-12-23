<?php
 /**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');

class register extends coffee{

	protected $result;

	private $newPasswrds;

	function __construct($passwArr){

		$this->newPasswrds = $passwArr[0];

		if(!empty($_SESSION['tempRegSes'][0])){
			$lastcheck = $this->finalCheck($_SESSION['tempRegSes'][0]);
			if($lastcheck == "true"){
				$complete = $this->completeUserRegistartion();
				if($complete == "success"){
					$this->result = "Registation Complete!!!";
				}else{
					return "Error";
				}
			}else{
				return "Error";
			}
		}
	}
	private function finalCheck($token){
		$vToken = strrev($token);
		parent::setQuery("SELECT `username` FROM `registration_tokens` WHERE `token` = '".$vToken."';");
		$a = parent::pdoExec();
		var_dump($a);
		if(!empty($a[0]['username'])){
			return "true";
		}else{
			return "false";
		}
	}
	private function completeUserRegistartion(){

		parent::setQuery("UPDATE`registration_tokens` SET `token` = null WHERE `token` ='".strrev($_SESSION['tempRegSes'][0])."';");
		parent::pdoExec();

		$hands = $this->creatLoginPass();

		parent::setQuery("UPDATE `usrlist` SET user_hash = '".$hands['h']."', user_salt='".$hands['s']."' WHERE user_name= '".$_SESSION['tempRegSes'][1]."';");
		parent::pdoExec();

		unset($_SESSION);
		session_destroy();
		session_unset();
		return "<script>alert('success');";
	}

	private function creatLoginPass(){
		$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)); 
		$hash = crypt($this->newPasswrds, sprintf('$2a$%02d$', 8) . $salt);

		return array('h' => $hash, 's' => $salt);
	}
}