<?php 
	require_once ($_SERVER['DOCUMENT_ROOT'].'/coffee2.0/_funct/coffee.php'); 
?>
<html>
	<head>
		<title>Coffee Time</title>
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

			    <?php
			    if(!isset($_SESSION['user'])){
					echo '<button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#modal-login">Login</button>
					<button onclick="window.location.href=\'/coffee2.0/register.php\'" type="button" class="btn btn-default navbar-btn">Registreren</button>';
				}else{
					echo '<button onclick="window.location.href=\'/coffee2.0/menu.php\'" type="button" class="btn btn-default navbar-btn">Go to Menu</button>
					<button onclick="window.location.href=\'/coffee2.0/logout.php\'" type="button" class="btn btn-default navbar-btn">Logout</button>';
				}
				?>
				<button type="button" class="btn btn-default navbar-btn" data-toggle="modal" data-target="#modal-about">About</button>
			</div>
		  </div>
		</nav>
		
		<?php 
			if(isset($_SESSION['user'])){
				echo $_SESSION['user'];
			}
			if(isset($_POST['login'])){
				$login = new coffee("login", array($_POST['usr'], $_POST['passwd']));
				echo $login->rtrnAll();
			}
		?>
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
			          <p>Style is made by Jelle Romfield</p>
			        </div>
			      </div>
			      
			    </div>
			  </div>

			  <!-- login-model -->
			  <div class="modal fade" id="modal-login" role="dialog">
			    <div class="modal-dialog">
			    
			      <!-- Modal content-->
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Login</h4>
			        </div>
			        <div class="modal-body">
			         <div class="container">
					
						<form method="post">
							<div class="form-group">
			   					<label for="exampleInputEmail1">username</label>
			    				<input type="text" class="form-control" title="usr" name="usr" placeholder="Username" required>
			  				</div>
			  				<div class="form-group">
			    				<label for="exampleInputPassword1">Password</label>
			   					<input type="password" class="form-control" title="passwd" name="passwd" placeholder="Password" required>
			 				 </div>
			  					
			  				<input class="login-btn btn-default" type="submit" title="login" name="login" value="login">
			  			</form>
						</div>
			        </div>
			      </div>
			      
			    </div>
			  </div>

			<footer class="footer">
	      		<div class="container">
	        		<p class="">&copy; 2015 Kevin Lorenzo Storms</p>
	      		</div>
	    	</footer>	
		<script type="text/javascript" src="./style/bower_components/jquery/dist/jquery.min.js"></script>
		<script type="text/javascript" src="./style/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	</body>
</html>