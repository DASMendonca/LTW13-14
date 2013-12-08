<?php
include 'classes.php';
session_start();


if(isset($_SESSION['customer']) && isset($_REQUEST["product_id"])){
	
	$this_date= (new DateTime())->format('Y-m-d');
	
	$db = new PDO('sqlite:../database.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
	$product_code= $_REQUEST["product_code"];
	$myCustomer=$_SESSION['customer'];
	$invoice_now;
	$invoice_line;
	$new_quantity;
	
	
	$customerQueryArr = array(array("CustomerID", array($myCustomer->CustomerID), "equal"));
	$productQueryArr = array( array("ProductCode", array($product_code), "equal"));
	
	$product = Product::getInstancesByFields($db, $productQueryArr);
	$product = $product[0];
	
	$taxQueryArr = array(array("TaxID", array($product->ProductCode), "equal"));
	
	$prod_tax= Tax::getInstancesByFields($db, $taxQueryArr);
	$prod_tax= $prod_tax[0];
	
	
	
	
	
	
	
	$invoices = Invoice::getInstancesByFields($db, $customerQueryArr);
	
	$isopen=0;
	foreach ($invoices as $invoice){
		if($invoice->InvoiceNo > $invoice_now->InvoiceNo)
			$invoice_now = $invoice;
		$isopen= $invoice_now->Status;
	}
	
	if(count($invoices)==0 || $isopen!=0){
		$invoice_now = new Invoice(null, $this_date, $this_date, 0);
		$invoice_now->Customer=$myCustomer;
		$invoice_now->insertIntoDB($db);
		
		$invoices = Invoice::getInstancesByFields($db, $customerQueryArr);
		$invoice_now= $invoices[0];
		
		
		$invoice_line = new Line($invoice_now->InvoiceNo, 1, 1, $this_date);
		$invoice_line->Product=$product;
		$invoice_line->Tax = $prod_tax;
		
		$invoice_lines->insertIntoDB($db);
	}
	else{
		//ha invoices e pode haver linha com esse produto
		
		$invoice_now = $invoices[0];
		foreach ($invoices as $invoice){
		if($invoice->InvoiceNo > $invoice_now->InvoiceNo)
			$invoice_now = $invoice;
		}
		
		$linesQueryArray= array( $productQueryArr, 
				array("InvoiceNo", array($invoice_now->InvoiceNo), "equal"));
		$invoice_lines = Line::getInstancesByFields($db, $linesQueryArray);
		$nr_of_lines= Line::getInstancesByFields($db, array("InvoiceNo", array($invoice_now->InvoiceNo), "equal"));
		
		$nr_of_lines = count($nr_of_lines) +1;
		
		
		if(count($invoice_lines)>0){
			$update_line=$invoice_lines[0];
			$new_quantity= $update_line->Quantity +1;	
			$updateQueryArray= array(
					array("InvoiceNo", $invoice_now->InvoiceNo),
					array("LineNo", $update_line->LineNo),
					array("Quantity", $new_quantity)
			);
			
			Line::updateInDB($db, $update_line);
		}
		
		else{
			$linha = new Line($InvoiceNumber, $LineNo, $Quantity, $LineDate);
			$invoice_line = new Line($invoice_now->InvoiceNo, $nr_of_lines, 1, $this_date);
			$invoice_line->Product=$product;
			$invoice_line->Tax = $prod_tax;
			
			$invoice_lines->insertIntoDB($db);
		}
	}	
	
}

?>