<?php
/**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  */
session_start();
ini_set('display_errors', 'On');

/* Default Database settings / login, templatePath. */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/config.php');
/* All functions that are executable by a user that is loged in. */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/dbUserAction.php');
/* Collects all Data of the logged-in user, and returns it */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/infoUser.php');
/* Executes the login, checks data, and validates/ renews session, sets session. */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/login.php');
/* Check the registartion token, and returns an finish registration form */
 require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/tokenCheck.php');
/* Finishing registration */
 require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/actionClasses/completeRegistration.php');

class coffee{
	//what to execute
	protected $what;
	//parameters
	protected $params;

	private $userFunctions;
	
	//General result Return var 
	private $results;
	//set Query var
	private $query;

	function __construct($what=string, $params=null){
		$this->what = $what;
		$this->params = $params;

		$this->process();
	}

	private function process(){
		//if not loggedin
		if(!isset($_SESSION['user'])){
			if($this->what == 'login'){
				$login = new login($this->params);
				$this->results = $login->result;
			}else if($this->what == 'checkToken'){
				$checkToken = new tokenCheck($this->params);
				$this->results = $checkToken->result;	
			}else if($this->what == 'register'){
				$register = new register($this->params);
				$this->results = $register->result;
			}
		}else{
			//validate session before executing the rest
			$sessCheckRes = $this->checkSession();
			//puts all existing functions in userFunctions.
			$this->userFunctions = unserialize(USERFUNCTS);
			//checks privileges
			if ($sessCheckRes == "1"){
				$this->userFunctions = $this->userFunctions['default'];
				
			}elseif($sessCheckRes == "2"){
				$this->userFunctions = $this->userFunctions['admin'];
			}else{
				//If your banned or there is something wrong
				$templateData = new infoUser();
				$this->results = $this->loadMenuTemplate($templateData->result[0], "default.phtml");
				$this->results .= "<script>alert('Sorry, but you cannot use the application at this moment.')</script>";
				exit();
			}
			try{
				//If function is available for user
				if(in_array($this->what, $this->userFunctions)){
						//*databaseuseraction
						$dua = new dbUserAction($this->what, $this->params);
						//execute and return
						$this->results = $dua->execUserAction();
				}else{
					return "sorry this is not possible";
				}
				$this->what = null;
				$this->params = null;
			}catch(exception $err){
				echo $err->getMessage();
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
		try{
		    $this->connPDO = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME."", DBUSERNAME, DBPASSWD);
		    // set the PDO error mode to exception
		    $this->connPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    // prepare sql and bind parameters
		    $stmt = $this->connPDO->prepare($this->query);
			$stmt->execute();
			if(!strpos($this->query, "UPDATE") || !strpos($this->query, "INSERT")){
				$data = $stmt->fetchAll(); 
			}
		    return $data;	

	    }catch(PDOException $e){
		   	echo "<br>Error: " . $e->getMessage();
	    } 

    	$this->query = null;
		$this->connPDO = null;	
	}

	protected function loadMenuTemplate($data, $template){
		$this->file = file_get_contents($_SERVER['DOCUMENT_ROOT'].TEMPLATEPATH.$template);

	    $this->fileFixed = $this->file;
	    //replace text
	    foreach ($data as $key => $value) {
	        $this->fileFixed = str_replace("[/" . $key . "\]", $value, $this->fileFixed);
	    }
	    return $this->fileFixed;
	}

	protected function checkSession(){
		$this->setQuery("SELECT * FROM `sessions` WHERE session_id= '".$_SESSION['user']."' LIMIT 1;");
		$test = $this->pdoExec();

		if(empty($test[0])){
			return "0";
		}else{			
			if(!empty($test[0]['person']) && strtotime($test[0]['expir_date']) > strtotime(date("Y-m-d H:i:s")) && $test[0]['priv_lvl'] != 0){
				return $test[0]['priv_lvl'];
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