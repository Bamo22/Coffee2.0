<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php'); 

if(!empty($_SESSION['user']) && isset($_SESSION['user'])){
	$getUserData = new coffee('renderTemplate');
	echo $getUserData->rtrnAll();

}else{
	echo "<script>alert('First Login');</script>";
	echo '<meta http-equiv="refresh" content="0; url=http://'.DOMAIN.'/coffee2.0/" />';
}

if(isset($_POST['createNewUser'])){

  $newUSer = new coffee('creat_new_user', $_POST);
  print_r($newUSer->rtrnAll());
}