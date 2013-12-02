<?php
include 'classes.php';
session_start();
?>
<script	src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src='edition.js'></script>


<?php

if(isset($_SESSION['customer'])){
	$db= new PDO('sqlite:./database.sqlite');
	$params=array(array("AddressID", array($_SESSION['customer']->addressID), "equal"));
	$myAddr= Address::getInstancesByFields($db, $params);
echo '
<div class="update_div" name="Customer">
		<br><br>
		<form action="updateMyuser.php" method="post" class="update_form" name="CustomerID" id="'.$_SESSION['customer']->CustomerID.'">
			<fieldset>
				<legend><h2>Edit Information</h2></legend>
				<div id="CompanyName">
				<label class="to_ident" for="">Name</label>
				<input type="text" name="CompanyName" id="CompanyName" placeholder="'.$_SESSION['customer']->CompanyName.'" value="'.$_SESSION['customer']->CompanyName.'"><br>
				</div>

				<div id="Email">
				<label class="to_ident" for="email">E-mail</label>
				<input type="text" name="email" id="email" placeholder="'.$_SESSION['customer']->email.'" value="'.$_SESSION['customer']->email.'"><br>
				</div>
						
				<div id="CustomerTaxID">
				<label class="to_ident" for="CustomerTaxID">Tax ID</label>
				<input type="number" name="CustomerTaxID" id="CustomerTaxID" placeholder="'.$_SESSION['customer']->CustomerTaxID.'" value="'.$_SESSION['customer']->CustomerTaxID.'"><br>
				</div>		

				<div id="Password">
				<label class="to_ident" for="password">Password</label>
				<input type="text" name="password" id="password" placeholder="'.$_SESSION['customer']->password.'" value="'.$_SESSION['customer']->password.'"><br>
				</div>
						
				<div id="Country">
				<label class="to_ident" for="Country">Country</label>
				<input type="text" name="Country" id="Country" placeholder="'.$myAddr[0]->country.'" value="'.$myAddr[0]->country.'"><br>
				</div>
				
				<div id="City>
				<label class="to_ident" for="City"></label>
				<input type="text" name="City" id="City" placeholder="'.$myAddr[0]->city.'" value="'.$myAddr[0]->city.'"><br>
				</div>
						
				<div id="AddressDetail">
				<label class="to_ident" for="AddressDetail"></label>
				<input type="text" name="AddressDetail" id="AddressDetail" placeholder="'.$myAddr[0]->detail.'" value="'.$myAddr[0]->detail.'"><br>
				</div>
					
				<div id="PostalCode1">
				<label class="to_ident" for="PostalCode1"></label>
				<input type="text" name="PostalCode1" id="PostalCode1" placeholder="'.$myAddr[0]->postalCode1.'" value="'.$myAddr[0]->postalCode1.'">-
				</div>
				<div id ="PostalCode2">
				<input type="text" name="PostalCode2" id="PostalCode2" placeholder="'.$myAddr[0]->postalCode2.'" value="'.$myAddr[0]->postalCode2.'"><br>
				</div>
				<input type="button" id="save_edit" value="Save">				
			</fieldset>
		</form>
</div>';	
}
?>