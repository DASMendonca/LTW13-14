<?php

include '../classes.php';

session_start();
header('Content-type: application/json');

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$costumers=array();

try {
	if(!isset($_GET["CustomerID"])) throw new GeneralException(new Err_MissingParameter("CustomerID"));
	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());
	
	$params=array(
			array("CustomerID",array($_GET["CustomerID"]),"equal")

	);
	
	$logedInUser=$_SESSION["customer"];
	$queriedID=$_GET["CustomerID"];
	
	if(($logedInUser->Permission==0 || $logedInUser->Permission==1) && $logedInUser->CustomerID!=$queriedID) throw  new GeneralException(new Err_PermissionDenied());
	

	$customers=Customer::getInstancesByFields($db, $params);
	if(count($customers)==0)throw new GeneralException(new Err_Not_Found("customers"));
	
	$stringFinal='[';
	
	$currentCustomerID = $customers[0]->CustomerID;
	$currentCustomerTaxID = $customers[0]->CustomerTaxID;
	$currentCustomerName = $customers[0]->CompanyName;
	
	$currentBillingAddressA = $customers[0]->BillingAddress->AddressDetail;
	$currentBillingCity = $customers[0]->BillingAddress->City;
	$currentBillingPC1 = $customers[0]->BillingAddress->PostalCode1;
	$currentBillingPC2 = $customers[0]->BillingAddress->PostalCode2;
	
	$currentBillingPC = $currentBillingPC1;
	$currentBillingPC .= '-';
	$currentBillingPC .= $currentBillingPC2;
	
	$currentBillingAddress = '{"AddressDetail" : "'.$currentBillingAddressA.'",
								"City" : "'.$currentBillingCity.'",
								"PostalCode" : "'.$currentBillingPC.'",
								"Country" : "PT"}';
	
	$currentCustomerEmail = $customers[0]->Email;
	
	$stringFinal .= '{"CustomerID" : "'.$currentCustomerID.'",
						  "CustomerTaxID" : "'.$currentCustomerTaxID.'",
						  "CompanyName" : "'.$currentCustomerName.'",
						  "BillingAddress" : '.$currentBillingAddress.',
						  "Email" : "'.$currentCustomerEmail.'"}';
	
	$stringFinal .=']';
	
	echo $stringFinal;

} catch (GeneralException  $e) {
	echo json_encode($e);
}catch (PDOException $e) {
	
	$exception=new GeneralException(new Err_DBProblem($e));
	echo json_encode($exception);
}

?>