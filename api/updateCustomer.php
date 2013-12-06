<?php

include '../classes.php';

session_start();//resume session

header('Content-type: application/json');
$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {
	
	$parameters=array();
	
	if(!isset($_SESSION["customer"])) throw new GeneralException(new Err_Autentication());
	
	
	if(!isset($_POST["customer"]))throw new GeneralException(new Err_MissingParameter("customer"));
	
	$customerPassed=json_decode($_POST["customer"]);
	$customerPassedAsArray=(array) $customerPassed;
	
	if(isset($customerPassed["BillingAddress"])) $addressAsArray=(array) $customerPassed["BillingAddress"];
	
	
	$user=$_SESSION["customer"];
	$userPermission=$user->Permission;
	
	if(isset($customerPassedAsArray["CustomerID"])){//if is an update
		
				
			//if user and is not updating himself
		if($userPermission<=1 && $user->CustomerID != $customerPassed->CustomerID) throw new GeneralException(new Err_PermissionDenied());//user cannot edit other users
		
		if(isset($customerPassedAsArray["CustomerID"])) array_push($parameters,array("CustomerID",$customerPassedAsArray["CustomerID"]));
		if(isset($customerPassedAsArray["CompanyName"]))array_push($parameters,array("CompanyName",$customerPassedAsArray["CompanyName"]));
		if(isset($customerPassedAsArray["CustomerTaxID"]))array_push($parameters,array("CustomerTaxID",$customerPassedAsArray["CustomerTaxID"]));
		if(isset($customerPassedAsArray["Email"]))array_push($parameters,array("Email",$customerPassedAsArray["Email"]));
		if(isset($customerPassedAsArray["Password"]))array_push($parameters,array("CompanyName",$customerPassedAsArray["CompanyName"]));
		if(isset($customerPassedAsArray["BillingAddress"])){
			if(isset($addressAsArray["AddressDetail"]))array_push($parameters,array("AddressDetail",$addressAsArray["AddressDetail"]));
			if(isset($addressAsArray["PostalCode1"]))array_push($parameters,array("PostalCode1",$addressAsArray["PostalCode1"]));
			if(isset($addressAsArray["PostalCode2"]))array_push($parameters,array("PostalCode2",$addressAsArray["PostalCode2"]));
			if(isset($addressAsArray["City"]))array_push($parameters,array("City",$addressAsArray["City"]));
			if(isset($addressAsArray["Country"]))array_push($parameters,array("Country",$addressAsArray["Country"]));	
		}
		
		if($userPermission==3){//if admin
			if(isset($customerPassedAsArray["Permission"]))array_push($parameters,array("Permission",$customerPassedAsArray["Permission"]));
			
		}
		
		
		//if there is nothing to update
		if(count($parameters)<=1){//if there is nothing to update
			$queryParameters=array(array("CustomerID"),array($parameters[0][1]),"equal");
			$customers=Customer::getInstancesByFields($db, $queryParameters);
			echo json_encode($customers[0]);
			die;
		}
		
		
		$customer=Customer::updateInDB($db, $parameters);
		echo json_encode($customer);
		
		
	}
	else{
		
		
		if($userPermission!=3){//cannot create users
			//TODO: do this case
			
		}
		
		
		
	}
	
	
	
	
	
} catch (GeneralException $e) {
	echo json_encode($e);
}




?>