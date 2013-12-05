<?php 
include 'classes.php';

$CompanyName = $_REQUEST['CompanyName'];
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
		$new_address = new Address($AddressDetail, $City, $PostalCode1, $PostalCode2, $Country);
		$myCustomer = new Customer(null, $CustomerTaxID, $CompanyName, $Email, $Password, null);
		$myCustomer->BillingAddress=$new_address;
		$myCustomer->insertIntoDB($db);
}


else
header("Location: onlineInvoiceSystem.php?msg=CustomerAlreadyInDB");
?>