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
	}
	private function declareExpense(){
		parent::setQuery('SELECT COUNT(coins) FROM `usrlist`;');
		$totalAmountJar = parent::pdoExec();
		$expense = $this->params['expense'] / $totalAmountJar[0][0];

		parent::setQuery("UPDATE `usrlist` SET coins=coins-".$expense.";");
		parent::pdoExec();
		parent::setQuery("INSERT INTO `expense_transactions` (total_amount, description) VALUES ('".$this->params['expense']."', '".$this->params['discrp']."');");
		parent::pdoExec();
		return "Proceed!";
	}
	private function joinCoffeeSession(){
		if(empty($_SESSION['coffeeSession'])){
		
			parent::setQuery("SELECT * FROM coffee_sessions WHERE session_name= '".$this->params."' LIMIT 1;");
			$cSession = parent::pdoExec();

			if($cSession[0]['joins'] < $cSession[0]['max_joins'] || $cSession[0]['joins'] != $cSession[0]['max_joins']){
				parent::setQuery("INSERT INTO coffee_session_candidates	 
								(session_id, user_name, cups_consumed) 
								SELECT '".$cSession[0]['session_id']."', user_name, 0 FROM `usrlist`
									WHERE id IN (
									SELECT person
	    							FROM `sessions`
	    							WHERE session_id = '".$_SESSION['user']."');");
				parent::pdoExec();
				parent::setQuery("UPDATE `coffee_sessions` SET joins= joins + 1 WHERE session_name= '".$this->params."';");
				parent::pdoExec();
				$_SESSION['coffeeSession'] = $cSession[0]['session_id'];
				return "JOINED!";
				
		}else{
			return "Sorry, Coffee session is full!";
		}
	}
}
	private function gatherSessionGroupDetails(){
		parent::setQuery("SELECT * FROM `coffee_sessions` WHERE session_id= '".$_SESSION['coffeeSession']."';");
		$ecvar = parent::pdoExec();
		$groupDetails['csess'] = $ecvar[0];
		parent::setQuery("SELECT * FROM `coffee_session_candidates` WHERE session_id= '".$_SESSION['coffeeSession']."';");
		$groupDetails['csessc'] = parent::pdoExec();
		return $groupDetails;
	}
	private function gatherAllUsers(){
		parent::setQuery('SELECT `id`, `user_name`, `coins` FROM usrlist;');
		return parent::pdoExec();
	}
	private function renderTemplate(){
		$templateData = new infoUser();	
		if(parent::checkSession() == 1){
			return parent::loadMenuTemplate($templateData->result[0], "default.phtml");	
		}else if (parent::checkSession() == 2) {
			return parent::loadMenuTemplate($templateData->result[0], "adminControl.phtml");
		}
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
		echo "<div class='alert alert-success' role='alert'>The registration token: ".$key."</div>";
	}
	private function createCoffeeSession(){
		parent::setQuery("SELECT `session_id` FROM `coffee_sessions` WHERE session_name= '".$this->prarams['name']."' LIMIT 1;");
		$sessId = parent::pdoExec();
		if(!empty($sessId[0]['session_id']) || is_null($sessId[0]['session_id'])){
			return "Sorry this name is already given to a session!";
		}else{
			if(isset($this->params['name']) && !empty($this->params['name']) || isset($this->params['maxjoins']) && !empty($this->params['maxjoins'])){
				if(!is_int($this->params['maxjoins']) || is_string($this->params['maxjoins'])){
					$this->params['maxjoins'] = intval($this->params['maxjoins']);
					if ($this->params['maxjoins'] > 10) {
						$this->params['maxjoins'] = 10;
					}
					parent::setQuery("INSERT INTO `coffee_sessions` (session_name, status, joins, max_joins) VALUES ('".$this->params['name']."', 'open', '0', '".$this->params['maxjoins']."');");
					parent::pdoExec();
					return "added new Coffee session!";
				}
		}else{
			return "Sorry not enough parameters set.";
		}
	}
}
	private function refreshCoffeeSessions(){
		parent::setQuery("SELECT `session_id` 
						    FROM `coffee_session_candidates` 
						        WHERE user_name IN (
						            SELECT user_name 
						                FROM `usrlist` 
						                    WHERE id IN ( 
						                        SELECT person FROM `sessions` WHERE session_id = '".$_SESSION['user']."')) LIMIT 1");
		$availableCoffeeSession = parent::pdoExec();
		if(!empty($availableCoffeeSession[0]['session_id'])){
			$_SESSION['coffeeSession'] = $availableCoffeeSession[0]['session_id'];
		}
		parent::setQuery("SELECT * FROM `coffee_sessions`;");
		return parent::pdoExec();
	}
}