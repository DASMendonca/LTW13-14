<?php
include './classes.php';
session_start();
header('Content-type: text/html');


if(!isset($_SESSION['customer']) || $_SESSION['customer']->Permission<2) 
	header("Location: onlineInvoiceSystem.php");
	

	$db = new PDO('sqlite:./database.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	
	$params=array(
			array("InvoiceNo",array($_GET["param"]),"equal")
	);
	

	$invoices = Invoice::getInstancesByFields($db, $params);
	$invoice= $invoices[0];
	$lines= $invoice->getLines();
	
		
	/********************************************
	 * 				Invoice Fields				*
	 * 											*
	 * 1- $invoice->getLines()					*
	 * 2- $invoice->getCustomerId()				*
	 * 3- $invoice->CompanyName					*
	 * 4- $invoice->GrossTotal					*
	 * 5- $invoice->InvoiceDate					*
	 * 6- $invoice->InvoiceNo					*
	 * 											*
	 ********************************************/
	$params=array(
		array("CustomerID", array($invoice->getCustomerId()), "equal")
	);
	
	$customers= Customer::getInstancesByFields($db, $params);
	$customer=$customers[0];
	 
	 echo '
<div class="update_div" name="Invoice" id="false">
		<br><br>
		<form action="updateMyInvoice.php" method="post" class="update_form" name="InvoiceNo" id="'.$invoice->InvoiceNo.'">
			<fieldset>
				<legend><h2>Edit Information</h2></legend>
				
				<div class="permanent">
					<label>Company Name</label>
					<label class="to_ident">'.$customer->CompanyName.'</label>
				</div><br>
						
				<div class="permanent">
					<label  class="to_ident">Tax ID</label>
					<label  class="to_ident">'.$customer->CustomerTaxID.'</label>
				</div><br>

				<div class="permanent">
					<label class ="to_ident">Invoice Nr.</label>
					<label class="to_ident">'.$invoice->InvoiceNo.'</label>
				</div><br>
							

				<div class="permanent">
					<label >Invoice Date</label>
					<label class="to_ident">'.$invoice->InvoiceDate.'</label>
				</div><br>
				
				<table class="products">
	<tr>
	<th>Product Code:</th>
			<th>Product Description:</th>
			<th>UN</th>
			<th>Quantity</th>
			<th>Unit Price</th>
			<th>Tax</th>
			<th>Total Price</th>
		</tr>';

	
	
	for($i=0;$i<count($lines);$i++){
		$line=$lines[$i];
		
		$productQueryParams=array(
		array("ProductCode",array($line->ProductCode),"equal")
		);
		
		$products=Product::getInstancesByFields($db, $productQueryParams);
		$product=$products[0];
		
		
		echo'<tr>';
		echo '<td>'.$line->ProductCode.'</td>';
		echo utf8_encode('<td>'.$product->ProductDescription.'</td>');
		echo utf8_encode('<td>'.$product->UnitOfMeasure.'</td>');
		echo utf8_encode('<td>'.$line->Quantity.'</td>');
		echo utf8_encode('<td>'.($line->UnitPrice/100).' &euro; </td>');
		echo utf8_encode('<td>'.$line->Tax->TaxPercentage.'</td>');
		echo utf8_encode('<td>'.((int)($line->CreditAmount*($line->Tax->TaxPercentage/100+1))/100).' &euro;</td>
		</tr>');
	}

	echo '</table>
	</div>';
	
?>