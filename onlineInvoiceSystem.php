<?php 
	session_start(); 
?>


<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="homepage.css">
<title>Online Invoice System</title>
<script
	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script type="text/javascript" src='login.js'></script>
</head>

<div id = "home_page">
	<body>
			<div id="topmenu">
				<ul>
					<?php
						if(!isset($_SESSION['customer']) ){
					?> 
					<li>Login:</li>
					<li>
						<form action="signin.php" method="GET" id="logForm">
							<input type="email" placeholder="email" name="Email" id="emailInput"> 
							<input type="password" placeholder="password" name="Password" id="pwInput"> 
							<input type="button" value="Sign In" id="SignInButton">
						</form>
					</li>
					<li>Products</li>
						<?php
						}
					?>
					<?php if(isset($_SESSION['customer']) ){
					?>
					<li>Invoices</li>
					<li>Clients</li>
					<li> <form action="logout.php" method="GET" id="logForm">
							<input type="submit" name="logout" value="logout" id="sair">
						</form>
					</li>
					
					<?php 
						
						}
					?>
				</ul>
			</div>
			
			<div id="mainDiv">
			Aqui vai ser usado ajax.
			</div>
		
	</body>
</div>