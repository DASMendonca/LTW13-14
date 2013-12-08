<?php
include 'classes.php';
session_start();


if(isset($_SESSION['customer']) && isset($_REQUEST["product_id"]){
	
	$db = new PDO('sqlite:../database.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
	$product_id= $_REQUEST["product_id"];
	$myCustomer=$_SESSION['customer'];
	$invoice_now;
	$invoice_line;
	
	
	$queryArr = array(array("CustomerID", array($myCustomer->CustomerID), "equal"));
	
	
	
	$invoices = Invoice::getInstancesByFields($db, $queryArr);
	
	if(count($invoices)==0){
		$invoice_now = new Invoice(null, new DateTime())->format('Y-m-d'), new DateTime())->format('Y-m-d'), 0);
		$invoice_now->Customer=$myCustomer;
		$invoice_now->insertIntoDB($db);
		
		$invoices = Invoice::getInstancesByFields($db, $queryArr);
		$invoice_now= $invoices[0];
	}
	else{
		
		$invoice_now = $invoices[0];
		foreach ($invoices as $invoice){
		if($invoice->InvoiceNo > $invoice_now->InvoiceNo)
			$invoice_now = $invoice;
		}
		
		$queryArr= array( array("ProductCode", array($product_id), "equal"), array("InvoiceNo"));
		$invoice_lines = Line::getInstancesByFields($db, $fields)
		
		
	}

	$
	
	
	
	$products = Product::getInstancesByFields($db, $queryArr);
	
	$product =$products[0];
	
	
	
	
}

?>