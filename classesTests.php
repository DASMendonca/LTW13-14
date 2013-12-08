<?php
include 'classes.php';

//$customer=new Customer(null, 5555555, "Fransisco", "maluco@gmail.com", 1234, 1);
//$customer_json='{"CustomerTaxID":12314335,"CompanyName":"Sonae","Email":"sonae@gmail.com","Password":1234,"Permission":1,"BillingAddress":{"AddressDetail":"Rua dos Clerigos","PostalCode1":4200,"PostalCode2":222,"City":"Porto","Country":"Portugal"}}';

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

/*
$invoice= new Invoice(null, "2013-12-12", "2013-12-12", "0");
$customer=Customer::getInstancesByFields($db, array(array("CustomerID",array(3),"equal")))[0];
$invoice->Customer=$customer;

$invoice->insertIntoDB($db)
*/
try{
	
	$parameters=array(
			array("InvoiceNo",1),
			array("LineNo",4),
			array("Quantity",16)
	);
	
	$line=Line::updateInDB($db, $parameters);
}
catch(GeneralException $e){
	echo $e;
}
?>
