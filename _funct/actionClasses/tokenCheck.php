<?php
 /**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
 require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');
 
class tokenCheck extends coffee{

	private $submtoken;
	private $srchres;
	protected $result;

	function __construct($input){
		if(empty($input)){ $this->result = "Please fill the input box";}
			if(!strlen($input) == 25){$this->result = "not a valid Token";}
		$this->submtoken = $input;

		$this->search();
		$searchres = $this->srchres;
		//echo "<pre>";var_dump($searchres);echo "</pre>";
		if(array($searchres) && !empty($searchres[0]['token']) && strtotime($searchres[0]['expiration_date']) > strtotime(date("Y-m-d H:i:s"))){
			//$this->result = $this->returnRegistration($searchres[0]['user_name']);
			$_SESSION['tempRegSes'] = array(strrev($searchres[0]['token']), $searchres[0]['user_name'], $this->returnRegistration($searchres[0]['user_name']));
		}else{
			$this->result = "This Token is not valid";
		}

	}

	private function search(){
		parent::setQuery("SELECT * 
			FROM `registration_tokens` 
			JOIN `usrlist` 
			ON registration_tokens.username = usrlist.id
			WHERE token= ".$this->submtoken.";");
		$this->srchres = parent::pdoExec();
		return $this->srchres;
	}
	
	private function returnRegistration($person){
		$f = "<div class='regform'>
				<form method='post'>
					<table class='form-group col-md-2'>
						<tr><td>Username:<input type='text' name='".$person."' value='".$person."' readonly/></td></tr>
						<tr><td><input type='password' name='passw1' title='passw1' placeholder='password' required/></td></tr>
						<tr><td><input type='password' name='passw2' title='passw2' placeholder='repeat password' required/></td></tr>
						<tr><td><input type='submit' name='register' titel='register' value='register'/></td></tr>
					</table>
				</form>
		</div>";
		return $f;
	}
}