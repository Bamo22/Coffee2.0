<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php');
/**
 *	@author K.L. Storms
 *	
 *	Description:
 *		Gets data from specivied user form the db, and returns all known data.
 *		Included menu content for template rendering.
 */
class infoUser extends coffee{

	public $result;

	function __construct(){
		$this->collectData();
	}

	private function collectData(){
		//Var that needs to be declared, otherwise it will parse an undev error.
		$userlistRow[0]['user_name'] = null;

		//Query for username, profile image path, total amount of money, lates login date.
		parent::setQuery("SELECT id, user_name, user_profile_pic, coins, lates_login FROM `usrlist` WHERE id IN ( SELECT person FROM `sessions` WHERE session_id = '".$_SESSION['user']."') LIMIT 1;");
		$usrlistRow = parent::pdoExec();
		//Query that gathers all consumed cups of coffee of a user.
		//parent::setQuery("SELECT SUM(cups_consumed) FROM `coffee_session_candidates` WHERE user= '".$userlistRow[0]['user_name']."';");
		 $cups = 0;

		parent::setQuery("SELECT SUM(coins) FROM `usrlist`;");
		$tcoins = parent::pdoExec();
		$usrlistRow[0]['tcoins'] = $tcoins[0][0];
		if(empty($usrlistRow[0]['user_profile_pic'])){$usrlistRow[0]['user_profile_pic'] = $_SERVER['DOCUMENT_ROOT']."/coffee2.0/style/imgs/profile_pics/default.png";}

		$cb = $_SERVER['DOCUMENT_ROOT']."/coffee2.0/style/imgs/profile_pics/".$usrlistRow[0]['user_profile_pic'];
		$path = $cb;
		$ext = pathinfo($path, PATHINFO_EXTENSION);

		$usrlistRow[0]['user_profile_pic'] = $this->base64_encode_image($cb, $ext);
		//First  login
		if(is_array($cups) || is_null($cups)){$cups = 0;}

		$usrlistRow[0]['cups_consumed'] = $cups;

		$this->result = $usrlistRow;
	}
	//Converts image file to base64
	public function base64_encode_image ($filename=string,$filetype=string) {
	    if ($filename) {
	        $imgbinary = fread(fopen($filename, "r"), filesize($filename));
	        return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
	    }
	}
}