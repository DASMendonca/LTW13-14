<?php
include 'classes.php';
session_start();


if(isset($_SESSION['customer'])){
	$db= new PDO('sqlite:./database.sqlite');
	$params=array(array("AddressID", array($_SESSION['customer']->addressID), "equal"));
	$myAddr= Address::getInstancesByFields($db, $params);
echo '
<div id="customer_edition">
		<br><br>
		<form action="updateMyuser.php" method="post" id="update_form">
			<fieldset>
				<legend><h2>Edit Information</h2></legend>
				<label class="to_ident" for="">Name</label>
				<input type="text" name="CompanyName" id="CompanyName" placeholder="'.$_SESSION['customer']->CompanyName.'"><br>
				
				<label class="to_ident" for="email">E-mail</label>
				<input type="text" name="email" id="email" placeholder="'.$_SESSION['customer']->email.'"><br>
				
				<label class="to_ident" for="CustomerTaxID">Tax ID</label>
				<input type="number" name="CustomerTaxID" id="CustomerTaxID" placeholder="'.$_SESSION['customer']->CustomerTaxID.'"><br>
						
				<label class="to_ident" for="password">Password</label>
				<input type="text" name="password" id="password" placeholder="password"><br>
						
				<label class="to_ident" for="Country">Country</label>
				<input type="text" name="Country" id="Country" placeholder="'.$myAddr[0]->country.'"><br>
						
				<label class="to_ident" for="City"></label>
				<input type="text" name="City" id="City" placeholder="'.$myAddr[0]->city.'"><br>
						
				<label class="to_ident" for="AddressDetail"></label>
				<input type="text" name="AddressDetail" id="AddressDetail" placeholder="'.$myAddr[0]->detail.'"><br>
						
				<label class="to_ident" for="PostalCode1"></label>
				<input type="text" name="PostalCode1" id="PostalCode1" placeholder="'.$myAddr[0]->postalCode1.'">-
				<input type="text" name="PostalCode2" id="PostalCode2" placeholder="'.$myAddr[0]->postalCode2.'"><br>
				<input type="button" value="Save">				
			</fieldset>
		</form>
</div>';	
}
?>