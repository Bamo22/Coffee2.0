<?php
//Sql Database:

define("DBNAME", "coffee", true);
define("DBHOST", "localhost", true);
define("DBUSERNAME", "root", true);
define("DBPASSWD", "", true);

//domain
define("DOMAIN", "localhost", true);

//template path
define("TEMPLATEPATH", "/coffee2.0/style/templ/", true);

//accessible admin and user functions
define("USERFUNCTS",serialize(["admin"=>['changeProfileImage', 
										 'renderTemplate', 
										 'new_user', 
										 'joinCoffeeSession',
										 'gatherAllUsers',
										 ''],
							"default"=>['changeProfileImage', 
							   			'renderTemplate', 
							   			'joinCoffeeSession']]), true);

//global errors
error_reporting(0);

?>