<?php

/**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');

class dbUserAction extends coffee{


	function __construct($what, $params){

		$this->what = $what;
		$this->params = $params;
	}

	protected function execUserAction(){
		$m = $this->what;
		if(empty($this->params) || $this->params == "" || $this->params == null){
			return $this->$m();
		}else{
			return $this->$m($this->params);
		}
		$this->what = null;
		$this->params = null;
	}
	private function changeProfileImage(){
		parent::setQuery("UPDATE `usrlist` 
						SET `user_profile_pic`= '".$this->params."' 
						WHERE id IN (
							SELECT person
							FROM `sessions`
							WHERE session_id = '".$_SESSION['user']."'
							);");
		parent::pdoExec();
		return "ok";
	}
	private function joinCoffeeSession(){
		//test
	}

	private function renderTemplate(){
		$templateData = new infoUser();
		return parent::loadMenuTemplate($templateData->result[0], "adminControl.phtml");
				
	}

	private function new_user(){
		$key = substr(bin2hex(mcrypt_create_iv(25, MCRYPT_DEV_URANDOM)),0, 22);
		
		parent::setQuery("SELECT `id` FROM `usrlist` WHERE `user_name`= '".$this->params['user_name']."'");
		$user_name = parent::pdoExec();
		
		if($this->params['money'] == null || is_string($this->params['money'])){
			$this->params['money'] = floatval($this->params['money']);
		}

		parent::setQuery("
			BEGIN;
				INSERT INTO usrlist 
				(user_name, user_profile_pic, coins)
				VALUES
				('".$this->params['user_name']."', 'default.png', '".$this->params['money']."');
				INSERT INTO registration_tokens
				(token, user_name, expiration_date) 
				VALUES
				('".$key."', LAST_INSERT_ID(), '".$this->params['expr_date']."');
			COMMIT;");
		parent::pdoExec();
		echo "<p> The registration token: ".$key."</p>";
	}
}