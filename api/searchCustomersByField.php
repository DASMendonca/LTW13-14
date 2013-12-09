<?php

header('Content-type: application/json');
include '../classes.php';

session_start();

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);






$customers=array();	
try {
	if(!isset($_SESSION["customer"]))throw new GeneralException(new Err_Autentication());
	else if($_SESSION["customer"]->Permission<3)throw new GeneralException(new Err_PermissionDenied());
	
	if(!isset($_GET["field"])) throw new GeneralException(new Err_MissingParameter("field"));
	if(!isset($_GET["value"])) throw new GeneralException(new Err_MissingParameter("value"));
	if(!isset($_GET["op"])) throw new GeneralException(new Err_MissingParameter("op"));
	
	
	$params=array(
		array($_GET["field"],$_GET["value"],$_GET["op"])
	);
	
	
	$customers=Customer::getInstancesByFields($db, $params);
	
	$stringFinal='[';
	
	for ($i=0;$i<(count($customers)-1);$i++){
		$currentCustomerID = $customers[$i]->CustomerID;
		$currentCustomerTaxID = $customers[$i]->CustomerTaxID;
		$currentCustomerName = $customers[$i]->CompanyName;
		
		$currentBillingAddressA = $customers[$i]->BillingAddress->AddressDetail;
		$currentBillingCity = $customers[$i]->BillingAddress->City;
		$currentBillingPC1 = $customers[$i]->BillingAddress->PostalCode1;
		$currentBillingPC2 = $customers[$i]->BillingAddress->PostalCode2;
		
		$currentBillingPC = $currentBillingPC1;
		$currentBillingPC .= ' - ';
		$currentBillingPC .= $currentBillingPC2;
		
		$currentBillingAddress = '{"AddressDetail" : "'.$currentBillingAddressA.'",
									"City" : "'.$currentBillingCity.'",
									"PostalCode" : "'.$currentBillingPC.'",
									"Country" : "PT"}';
		
		$currentCustomerEmail = $customers[$i]->Email;
		
		$stringFinal .= '{"CustomerID" : "'.$currentCustomerID.'",
						  "CustomerTaxID" : "'.$currentCustomerTaxID.'",
						  "CompanyName" : "'.$currentCustomerName.'",
						  "BillingAddress" : '.$currentBillingAddress.',
						  "Email" : "'.$currentCustomerEmail.'"},';
	}
	
	$currentCustomerID = $customers[$i]->CustomerID;
	$currentCustomerTaxID = $customers[$i]->CustomerTaxID;
	$currentCustomerName = $customers[$i]->CompanyName;
	
	$currentBillingAddressA = $customers[$i]->BillingAddress->AddressDetail;
	$currentBillingCity = $customers[$i]->BillingAddress->City;
	$currentBillingPC1 = $customers[$i]->BillingAddress->PostalCode1;
	$currentBillingPC2 = $customers[$i]->BillingAddress->PostalCode2;
	
	$currentBillingPC = $currentBillingPC1;
	$currentBillingPC .= ' - ';
	$currentBillingPC .= $currentBillingPC2;
	
	$currentBillingAddress = '{"AddressDetail" : "'.$currentBillingAddressA.'",
									"City" : "'.$currentBillingCity.'",
									"PostalCode" : "'.$currentBillingPC.'",
									"Country" : "PT"}';
	
	$currentCustomerEmail = $customers[$i]->Email;
	
	$stringFinal .= '{"CustomerID" : "'.$currentCustomerID.'",
						  "CustomerTaxID" : "'.$currentCustomerTaxID.'",
						  "CompanyName" : "'.$currentCustomerName.'",
						  "BillingAddress" : '.$currentBillingAddress.',
						  "Email" : "'.$currentCustomerEmail.'"}';
	
	$stringFinal .=']';
	
	echo $stringFinal;
	
} catch (GeneralException $e) {
	echo json_encode($e);
}catch (PDOException $e) {
	
	$exception=new GeneralException(new Err_DBProblem($e));
	echo json_encode($exception);
}





?>