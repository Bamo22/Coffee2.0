<?php
 /**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');

class register extends coffee{

	protected $result;
	private $userID;

	private $newPasswrds;

	function __construct($passwArr){

		$this->newPasswrds = $passwArr[0];

		if(!empty($_SESSION['tempRegSes'][0])){
			$lastcheck = $this->finalCheck($_SESSION['tempRegSes'][0]);
			if($lastcheck == "true"){
				return $this->completeUserRegistartion();
			}else{
				return "Error";
			}
		}
	}
	private function finalCheck($token){
		$vToken = strrev($token);
		parent::setQuery("SELECT `user_name` FROM `registration_tokens` WHERE `token` = '".$vToken."';");
		$a = parent::pdoExec();
		$this->userID = $a[0]['user_name'];
		if(!empty($this->userID)){
			return "true";
		}else{
			return "false";
		}
	}
	private function completeUserRegistartion(){

		parent::setQuery("DELETE FROM `registration_tokens` WHERE `token` ='".strrev($_SESSION['tempRegSes'][0])."';");
		parent::pdoExec();

		$hands = $this->creatLoginPass();

		parent::setQuery("UPDATE `usrlist` SET user_hash = '".$hands['h']."', user_salt='".$hands['s']."' WHERE user_name= '".$_SESSION['tempRegSes'][1]."';");
		parent::pdoExec();

		$newHash = substr(bin2hex(mcrypt_create_iv(14, MCRYPT_DEV_URANDOM)), 0, 13);

		$newExpirData = date('Y-m-d H:i:s', time() + (7 * 24 * 60 * 60));
		//echo "UPDATE `sessions` SET `session_id` = '".$newHash."', `expir_date` = '".$newExpirData."' WHERE `person` = '".$this->sqlResults['login'][0]['id']."';";
		parent::setQuery("INSERT INTO `sessions` (session_id, person, expir_date, priv_lvl) VALUES ('".$newHash."', '".$this->userID."', '".$newExpirData."', 1);");
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