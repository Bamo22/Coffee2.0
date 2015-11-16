<?php
/**
  * @author Kevin Lorenzo Storms
  * @version 2.0
  *
  * This file receives uploaded profile pictures.
  * Processes it in the database, and it stores the image serverside with a random generaded name.
  * 
  */
require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php'); 






//if a File is beeing upload
if(isset($_FILES)){
    //checks if there is a file received.
    if ($_FILES['file']['error'] < 0) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    } else {
    //check the extension of the file
    $path = $_FILES['file']['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);

    //generate a random string of 29 chars.
		$newname = substr( bin2hex(mcrypt_create_iv(25, MCRYPT_DEV_URANDOM)),0, 25).".".$ext;
    //Updates the profile pictures filename in the database.
		$change_profile_picture = new coffee('change_profile_pic', $newname);

		move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/coffee2.0/style/imgs/profile_pics/' . $newname);
    }
  }

?>