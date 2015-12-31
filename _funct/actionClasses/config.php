<?php
//Sql Database:

define("DBNAME", "coffee", true);
define("DBHOST", "localhost", true);
define("DBUSERNAME", "root", true);
define("DBPASSWD", "0>cB3mF13", true);

//domain
define("DOMAIN", "klst.uk", true);

//template path
define("TEMPLATEPATH", "/coffee2.0/style/templ/", true);

//accessible admin and user functions
define("USERFUNCTS",serialize(["admin"=>['changeProfileImage', 
										 'renderTemplate', 
										 'new_user', 
										 'joinCoffeeSession',
										 'gatherAllUsers',
										 'createCoffeeSession',
										 'refreshCoffeeSessions'],
							"default"=>['changeProfileImage', 
							   			'renderTemplate', 
							   			'joinCoffeeSession',
							   			'refreshCoffeeSessions']]), true);

//global errors
error_reporting();

?>
