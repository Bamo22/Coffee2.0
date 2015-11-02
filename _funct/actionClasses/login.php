<?php
/**
 * @author K.L. Storms
 *
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');

class login extends coffee{

	//inputData
	public $rData;
	//all sql query rows and things
	private $sqlResults;
	
	private $user;
	private $password;

	//the data that is needed in the coffeeProcess class 
	protected $result;

	public function __construct($rData){

		$this->rData = $rData;
		$this->user = $this->rData[0];
		$this->password = $this->rData[1];

		if(!is_array($rData)){$this->result = 'Err::noArray';}
			if(empty($this->user) || empty($this->password)){$this->result = 'Err::noData';}
				$this->execAll();

	}

	private function execAll(){

		$loginCheckRes = $this->login();
			//if login is successful
			if($loginCheckRes == 'yes'){
				$_SESSION['user'] = $this->assignSession();
				$this->updateLatestLogin();
				$this->result = '<meta http-equiv="refresh" content="0; url=http://'.DOMAIN.'/coffee2.0/menu.php" />';
			}else{
				$this->result = 'Wrong username / password';
			}

	}
	
	//Normal login check, if input data is valid our not
	private function login(){
		parent::setQuery("SELECT id, user_hash, user_salt FROM `usrlist` WHERE `user_name` = '".$this->user."' LIMIT 1;");
		$this->sqlResults['login'] = parent::pdoExec();
		//var_dump($this->sqlResults);
		$passwCheck = $this->validateHashess($this->password);
		
		if($passwCheck == 'valid'){
			return 'yes';
			//var_dump($passwCheck);
		} else {
			return '0';
		}
	}

	private function assignSession(){
		parent::setQuery("SELECT * FROM `sessions` WHERE `person`= '".$this->sqlResults['login'][0]['id']."' LIMIT 1;");
		$this->sqlResults['sessionCheck'] = parent::pdoExec();
		

			if(strtotime($this->sqlResults['sessionCheck'][0]['expir_date']) < strtotime(date("Y-m-d H:i:s"))){
				
				$_SESSION['user'] = $this->renewSession();
				return $_SESSION['user'];

			}else if(strtotime($this->sqlResults['sessionCheck'][0]['expir_date']) > strtotime(date("Y-m-d H:i:s"))){

				$_SESSION['user'] = $this->sqlResults['sessionCheck'][0]['session_id'];
				return $_SESSION['user'];
			}else{
				//not a valid session
			}				

	}

	private function updateLatestLogin(){
		$d = date('Y-m-d H:i:s');
		parent::setQuery("UPDATE `usrlist` SET `lates_login`= '".$d."' WHERE `id`= '".$this->sqlResults['login'][0]['id']."';");
		parent::pdoExec();
	}

	private function renewSession(){
		
		//     $stmt = $this->connPDO->prepare("SELECT * FROM `sessions` WHERE `person` = '".$this->sqlResults['login']['id']."';");
			$newHash = substr(bin2hex(mcrypt_create_iv(14, MCRYPT_DEV_URANDOM)), 0, 14);

			$newExpirData = date('Y-m-d H:i:s', time() + (7 * 24 * 60 * 60));
			//echo "UPDATE `sessions` SET `session_id` = '".$newHash."', `expir_date` = '".$newExpirData."' WHERE `person` = '".$this->sqlResults['login'][0]['id']."';";
			parent::setQuery("UPDATE `sessions` SET `session_id` = '".$newHash."', `expir_date` = '".$newExpirData."' WHERE `person` = '".$this->sqlResults['login'][0]['id']."';");
			parent::pdoExec();

			return $newHash;
	}

		private function validateHashess($pasw){
		    //echo $this->result['user_salt']; echo $this->result['user_hash'];
		    //print_r($this->sqlResults['login'][0]);
		    $restructHash = crypt($pasw, sprintf('$2a$%02d$', 8).$this->sqlResults['login'][0]['user_salt']);
		    if($restructHash == $this->sqlResults['login'][0]['user_hash']){
		    	return 'valid';
		    } else {
		    	return '0';
			}
		}

	}