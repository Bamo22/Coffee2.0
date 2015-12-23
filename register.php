<?php 
	require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php'); 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	<link rel="stylesheet" type="text/css" href="./style/milkyStyle.css">
	<link rel="shortcut icon" href="./style/imgs/icon.ico">
</head>
<body>
	<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="/coffee2.0"><p>Coffee Project</p></a>
		    </div>
		    <div class="align-nav-buttons">
					<button onclick="window.location.href='/coffee2.0/'" type="button" class="btn btn-default navbar-btn">Go to Homepage</button>
				<button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#modal-about">About</button>
			</div>
		  </div>
		</nav>
		<div class="container">
			<div class="panel panel-default">
			
			<form method="post">
			<label>Registration form,<br> please enter a valid registration token</label>
			 <div class="panel-body">
			    <div class="input-group">
			      <input type="text" class="form-control" name="token" title="token" placeholder="Token" required>
			      <span class="input-group-btn">
			       <input class="btn btn-default" type="submit" name="GO" title="GO" value="GO">
			      </span>
			      </form>
			    </div><!-- /input-group -->
			  
		
		
	<?php
	//print_r($_SESSION);
		if(isset($_POST['GO']) && !empty($_POST['token'])){
			$regis = new coffee("checkToken", $_POST['token']);
			echo $regis->rtrnAll();
			header('Location: http://localhost/coffee2.0/register.php');
		}
		if(isset($_POST['register'])){
			if(isset($_POST['passw1']) && isset($_POST['passw2'])){
				if($_POST['passw1'] === $_POST['passw2']){
					$complReg = new coffee("register", array($_POST['passw1'], $_POST['passw2']));
					var_dump($complReg->rtrnAll());
					header('Location: http://localhost/coffee2.0/register.php');
				}else{
					echo "the passwords do not match";
				}
			}
		}
		if(isset($_SESSION['tempRegSes']) && !empty($_SESSION['tempRegSes'][2])){
			echo $_SESSION['tempRegSes'][2];
		}
	?>
	</div><!-- /.col-lg-6 -->
	 </div>
	</div>
	<!-- about-model -->
			  <div class="modal fade" id="modal-about" role="dialog">
			    <div class="modal-dialog">
			    
			      <!-- Modal content-->
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">About Coffee Project</h4>
			        </div>
			        <div class="modal-body">
			          <p>This is a simple reservation (coffee) system.</p>
			          <p>This web app is specially made for developers who share there coffeemachine with each other.
			          So they can share the costs for producing coffee.</p>
			          <p>Made By Kevin Lorenzo Storms</p>
			          <p>Style made by Jelle Romfield</p>
			        </div>
			      </div>
			      
			    </div>
			  </div>

		<footer class="footer">
	      		<div class="container">
	        		<p class="text-muted">&copy; 2015 Kevin Lorenzo Storms</p>
	      		</div>
	    	</footer>
	<script type="text/javascript" src="./style/bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="./style/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>