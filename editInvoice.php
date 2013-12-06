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
			array("InvoiceNO",array($_GET["param"]),"equal")
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
	
	$customer= Customer::getInstancesByFields($db, $params);
	 
	 echo '
<div class="update_div" name="Invoice" id="false">
		<br><br>
		<form action="updateMyInvoice.php" method="post" class="update_form" name="InvoiceNo" id="'.$invoice->InvoiceNo.'">
			<fieldset>
				<legend><h2>Edit Information</h2></legend>
				<div class="permanent">
				<label>Company Name</label>
				<label class="to_ident" value="'.$customer->CompanyName.'"></label>
				</div><br>
						
				<div class="permanent">
				<label  class="to_ident">Tax ID</label>
				<label  class="to_ident" value="'.$customer->CustomerTaxID.'"></label>
				</div><br>	

				<div class="permanent">
				<label class="to_ident">Invoice Date</label>
				<label class="to_ident" value="'.$invoice->InvoiceDate.'"></label>
				</div><br>
						
				<div id="Country">
				<label class="to_ident" for="Country">Country</label>
				<input type="text" name="Country" id="Country" placeholder="'.$customer->getAddress()->Country.'" value="'.$customer->getAddress()->Country.'"><br>
				</div>
						
				
				<div id="City">
				<label class="to_ident" for="City"></label>
				<input type="text" name="City" id="City" placeholder="'.$customer->getAddress()->City.'" value="'.$customer->getAddress()->City.'"><br>
				</div>
						
				<div id="AddressDetail">
				<label class="to_ident" for="AddressDetail"></label>
				<input type="text" name="AddressDetail" id="AddressDetail" placeholder="'.$customer->getAddress()->AddressDetail.'" value="'.$customer->getAddress()->AddressDetail.'"><br>
				</div>
					
				<div id="PostalCode1">
				<label class="to_ident" for="PostalCode1"></label>
				<input type="text" name="PostalCode1" id="PostalCode1" placeholder="'.$customer->getAddress()->PostalCode1.'" value="'.$customer->getAddress()->PostalCode1.'">-
				</div>
				<div id ="PostalCode2">
				<input type="text" name="PostalCode2" id="PostalCode2" placeholder="'.$customer->getAddress()->PostalCode2.'" value="'.$customer->getAddress()->PostalCode2.'"><br>
				</div>
				<input type="button" id="save_edit" value="Save">				
			</fieldset>
		</form>
</div>';
	
?>