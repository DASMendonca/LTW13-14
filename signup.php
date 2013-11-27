<?php 
include 'classes.php';

$CustomerName = $_REQUEST['CustomerName'];
$CustomerTaxID = $_REQUEST['CustomerTaxID'];
$Email = $_REQUEST['Email'];
$Password = $_REQUEST['Password'];
$Country = $_REQUEST['Country'];
$City = $_REQUEST['City'];
$AddressDetail = $_REQUEST['AddressDetail'];
$PostalCode1 = $_REQUEST['PostalCode1'];
$PostalCode2 = $_REQUEST['PostalCode2'];
//$ = $_REQUEST[];

$db = new PDO('sqlite:./database.sqlite');

$myAddrID;


$params=array(array("CustomerTaxID", array($CustomerTaxID), "equal"), array("Email", array($Email), "equal"));
$customer_exists= Customer::getInstancesByFields($db, $params);

if(count($customer_exists)==0){
	
	$params = array(array("Country", array($Country), "equal"), array("City", array($City), "equal"),
			array("PostalCode1", array($PostalCode1), "equal"), array("PostalCode2", array($PostalCode2), "equal"));
	
	$addr_exists= Address::getInstancesByFields($db, $params);
	if(count($addr_exists)>0){
		$myAddrID= $addr_exists[0]->AddressID;
	}
	else{
		$new_address = new Address(null, $AddressDetail, $City, $PostalCode1, $PostalCode2, $Country);
		$new_address->saveToDB($db);
		$addr_exists= Address::getInstancesByFields($db, $params);
		$myAddrID=$addr_exists->AddressID;
	}
	$myCustomer = new Customer(null, $CustomerTaxID, $CustomerName, $myAddrID, $Email, $Password, 1, $db);
	
	$myCustomer->saveToDB($db);
}



header("Location: onlineInvoiceSystem.php");






?>