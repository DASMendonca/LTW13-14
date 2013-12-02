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
				<div id="CompanyName">
				<label class="to_ident" for="">Name</label>
				<input type="text" name="CompanyName" id="CompanyName" value="'.$_SESSION['customer']->CompanyName.'"><br>
				</div>

				<div id="Email">
				<label class="to_ident" for="email">E-mail</label>
				<input type="text" name="email" id="email" value="'.$_SESSION['customer']->email.'"><br>
				</div>
						
				<div id="CustomerTaxID">
				<label class="to_ident" for="CustomerTaxID">Tax ID</label>
				<input type="number" name="CustomerTaxID" id="CustomerTaxID" value="'.$_SESSION['customer']->CustomerTaxID.'"><br>
				</div>		

				<div id="Password">
				<label class="to_ident" for="password">Password</label>
				<input type="text" name="password" id="password" value="'.$_SESSION['customer']->password.'"><br>
				</div>
						
				<div id="Country">
				<label class="to_ident" for="Country">Country</label>
				<input type="text" name="Country" id="Country" value="'.$myAddr[0]->country.'"><br>
				</div>
				
				<div id="City>
				<label class="to_ident" for="City"></label>
				<input type="text" name="City" id="City" value="'.$myAddr[0]->city.'"><br>
				</div>
						
				<div id="AddressDetail">
				<label class="to_ident" for="AddressDetail"></label>
				<input type="text" name="AddressDetail" id="AddressDetail" value="'.$myAddr[0]->detail.'"><br>
				</div>
					
				<div id="PostalCode1">
				<label class="to_ident" for="PostalCode1"></label>
				<input type="text" name="PostalCode1" id="PostalCode1" value="'.$myAddr[0]->postalCode1.'">-
				</div>
				<div id ="PostalCode2">
				<input type="text" name="PostalCode2" id="PostalCode2" value="'.$myAddr[0]->postalCode2.'"><br>
				</div>
				<input type="button" value="Save">
			</fieldset>
		</form>
</div>';
	
	} catch (GeneralException  $e) {
		echo json_encode($e);
	}
	
?>