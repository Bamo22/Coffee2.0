<?php
/**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
session_start();
ini_set('display_errors', 'On');
//error_reporting(E_ALL);

 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/dbUserAction.php');
/* Collects all Data of the logged-in user, and returns it */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/infoUser.php');
/* Executes the login, checks data, and validates/ renews session, sets session. */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/login.php');
/* Default Database settings / login, templatePath. */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/config.php');
/* Check the registartion token, and returns an finish registration form */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/tokenCheck.php');

 require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/completeRegistration.php');

//echo $_SESSION['user'];

class coffee{

	//what to execute
	protected $what;
	//parameters
	protected $params; 	

	private $sessCheckRes;

	
	//General result Return var 
	private $results;
	//set Query var
	private $query;

	function __construct($_what, $params=null){
		$this->_what = $_what;
		$this->params = $params;

			$this->executeMain();
	}

	private function executeMain(){
		//if not loggedin
		if(!isset($_SESSION['user'])){
			if($this->_what == 'login'){
				$login = new login($this->params);
				$this->results = $login->result;
			}else if($this->_what == 'checkToken'){
				$checkToken = new tokenCheck($this->params);
				$this->results = $checkToken->result;
			}else if($this->_what == 'register'){
				$register = new register($this->params);
				$this->results = $register->result;
			}
		}else{
			//validate session before executing the rest
			$this->checkSession();
			
			//1  = Default User
			if($this->sessCheckRes == "1"){
				if($this->_what == "renderTemplate"){
					$templateData = new infoUser();

					$this->results = $this->loadMenuTemplate($templateData->result[0], "default.phtml");
				}
				else if($this->_what == "change_profile_pic"){
					$change_profile_pic = new dbUserAction("changeProfileImage", $this->params);
					$this->results = $change_profile_pic;
				}

			//2 = Admin User
			}else if($this->sessCheckRes == "2"){
				//render template
				if($this->_what == "renderTemplate"){
					$templateData = new infoUser();

					$this->results = $this->loadMenuTemplate($templateData->result[0], "adminControl.phtml");
				}
				else if($this->_what == "change_profile_pic"){
					$change_profile_pic = new dbUserAction("changeProfileImage", $this->params);
				}
				else if($this->_what == "creat_new_user"){
					$new_user = new dbUserAction("new_user", $this->params);
					$this->results = $new_user;

				}else if($this->sessCheckRes == "ban"){
				$this->results = "Sorry, you're banned!";
			}else{
				return '<meta http-equiv="refresh" content="0; url=http://'.DOMAIN.'/coffee2.0/?A=flin" />';
			}
		}
	}
}
	protected function setQuery($q){
		$this->query = $q;
	}

	protected function pdoExec(){

		if(empty($this->query)){
			return "Not a valid query";
		}
		try {
		    $this->connPDO = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME."", DBUSERNAME, DBPASSWD);
		    // set the PDO error mode to exception
		    $this->connPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    // prepare sql and bind parameters
		    $stmt = $this->connPDO->prepare($this->query);
			$stmt->execute();

		    $data = $stmt->fetchAll(); 
		    return $data;	

	    } catch(PDOException $e) {
		   //echo "<br>Error: " . $e->getMessage();
	    } 

    	$this->query = null;
		$this->connPDO = null;	
	}

	private function loadMenuTemplate($data, $template){
		$this->file = file_get_contents($_SERVER['DOCUMENT_ROOT'].TEMPLATEPATH.$template);

	    $this->fileFixed = $this->file;
	    //replace text
	    foreach ($data as $key => $value) {
	        $this->fileFixed = str_replace("[/" . $key . "\]", $value, $this->fileFixed);
	    }
	    return $this->fileFixed;
	}

	private function checkSession(){
		$this->setQuery("SELECT * FROM `sessions` WHERE session_id= '".$_SESSION['user']."' LIMIT 1;");
		$test = $this->pdoExec();

		if(empty($test[0])){
			return "0";
		}else{			
			if(!empty($test[0]['person']) && strtotime($test[0]['expir_date']) > strtotime(date("Y-m-d H:i:s")) && $test[0]['priv_lvl'] != 0){
				$this->sessCheckRes = $test[0]['priv_lvl'];
			} else if($test[0]['priv_lvl'] == 0){
				return "ban";
			}else{
				return "0";
			}
		}
	}


	public function rtrnAll(){
			return $this->results;
	}	
}

//if(!$_SESSION['user']){ $_SESSION['user'] = "Guest";}

if($_GET){
	if($_GET['A']){
		if($_GET['A'] == 'logout'){
			unset($_SESSION['user']);
			unset($_SESSION);
			session_destroy();
			session_unset();
			header('Location: http://'.DOMAIN.'/coffee2.0');
		}
		if ($_GET['A'] == 'flin') {
			echo 'First login!';
		}
	}
}