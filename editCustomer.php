<?php
include '../classes.php';
header('Content-type: application/json');
session_start();

if(!isset($_SESSION['customer']) || $_SESSION['customer']->permission<2) 
	header("Location: onlineInvoiceSystem.php");



$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$costumers=array();

try {
	if(!isset($_REQUEST["CustomerID"])) throw new GeneralException(new Err_MissingParameter("CustomerID"));

	$params=array(
			array("CustomerID",array($_REQUEST["CustomerID"]),"equal")

	);

	$customers=Customer::getInstancesByFields($db, $params);
	if(count($customers)!=1)throw new GeneralException(new Err_Not_Found("customers"));
	 $customer=$customers[0];
	 echo '
<div id="customer_edition" name="'.$customer->CustomerID.'">
		<br><br>
		<form action="updateMyuser.php" method="post" class="update_form">
			<fieldset>
				<legend><h2>Edit Information</h2></legend>
				<label class="to_ident" for="CompanyName">Name</label>
				<input type="text" name="CompanyName" id="CompanyName" placeholder="'.$customer->CompanyName.'"><br>
	 
				<label class="to_ident" for="email">E-mail</label>
				<input type="text" name="email" id="email" placeholder="'.$customer->email.'"><br>
	 
				<label class="to_ident" for="CustomerTaxID">Tax ID</label>
				<input type="number" name="CustomerTaxID" id="CustomerTaxID" placeholder="'.$customer->CustomerTaxID.'"><br>
	 
				<label class="to_ident" for="password">Password</label>
				<input type="text" name="password" id="password" placeholder="password"><br>
	 
				<label class="to_ident" for="Country">Country</label>
				<input type="text" name="Country" id="Country" placeholder="'.$customer->country.'"><br>
	 
				<label class="to_ident" for="City"></label>
				<input type="text" name="City" id="City" placeholder="'.$customer->city.'"><br>
	 
				<label class="to_ident" for="AddressDetail"></label>
				<input type="text" name="AddressDetail" id="AddressDetail" placeholder="'.$customer->detail.'"><br>
	 
				<label class="to_ident" for="PostalCode1"></label>
				<input type="text" name="PostalCode1" id="PostalCode1" placeholder="'.$customer->postalCode1.'">-
				<input type="text" name="PostalCode2" id="PostalCode2" placeholder="'.$customer->postalCode2.'"><br>
				<input type="button" value="Save">
			</fieldset>
		</form>
</div>';
	
	} catch (GeneralException  $e) {
		echo json_encode($e);
	}
	
?>