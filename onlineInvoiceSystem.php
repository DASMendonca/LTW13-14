<?php 
	include 'classes.php';
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
<script type="text/javascript" src='search.js'></script>
<script type="text/javascript" src='edition.js'></script>
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
					<li class="top_lis" id="li_signup">Signup</li>
						<?php
						} echo '<li id="li_prod" class="top_lis">Products</li>';
					?>
					<?php if(isset($_SESSION['customer']) ){
					?>
					<li class="top_lis" id="li_myInvoices" name="myInvoices">My Invoices</li>
					<li class="top_lis" id="li_myProfile" name="myProfile">My Profile</li>
					<?php 
						if (($_SESSION['customer']->permission)>1) {
							echo '	<li class="top_lis" id="li_invoice">Search Invoices</li>
									<li class="top_lis" id="li_customer">Search Clients</li>
									<li class="top_lis" id="li_administration">Administrative section</li>';
						}
					?>
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