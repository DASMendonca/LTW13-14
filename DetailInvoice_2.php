<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="DetailsPrint_2.css" media="print">
<link rel="stylesheet" href="Details_2.css" media="screen">
<title>DetailInvoice</title>
</head>

<body>
	<table class="Logo">
		<tr>
			<th>Online Invoice System</th>
		</tr>
		<tr>
			<td>Linguagens e Tecnologias Web</td>
		</tr>
	</table>

	<?php 
include './classes.php';
$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);



try {
	if(!isset($_GET['params']))
		throw new GeneralException(new Err_MissingParameter("params"));

	$params=array(json_decode($_GET["params"]));



	$invoices=invoice::getInstancesByFields($db, $params);
	$invoice=$invoices[0];
	
	$invoiceCode=$invoice->InvoiceNo;
	$invoiceDate=$invoice->InvoiceDate;
	$invoiceCompanyName=$invoice->CompanyName;
	$invoiceGrossTotal=$invoice->GrossTotal;
	$invoiceCustomerID=$invoice->getCustomerId();
	
	$customerQueryParams=array(
		array("CustomerID",array($invoice->getCustomerId()),"equal")
	);
	
	$customers=Customer::getInstancesByFields($db, $customerQueryParams);
	$custormer=$customers[0];
	
	$invoiceNif=$custormer->CustomerTaxID;
	$invoiceLines =$invoice->getLines();
	
} catch (GeneralException $e) {

	$invoiceCode="0";
	$invoiceDate="1970-1-1";
	$invoiceCompanyName="No Company";
	$invoiceGrossTotal="0";
	$invoiceCustomerID="0";
	$invoiceNif="0";
	$invoiceLines=array();

}

	
	echo '<p class="sheetID">Customer Data</p>
			<br><br>
			<p class="rowID">Customer Code: </p>
			<p>'.$invoiceCustomerID.'</p>
			<br>
			<p class="rowID">Name: </p>';
	echo '<p>'.$invoiceCompanyName.'</p>
			<br>
			<p class="rowID">NIF: </p>';
	echo '<p>'.$invoiceNif.'</p>
			<br><br>';

	echo '<p class="sheetID">Invoice</p>
			<br>
			<table>
			<tr>
			<td class="rowID">Invoice Nr: </td>
			<td>'.$invoiceCode.'</td>
			<td class="rowID">Date: </td>';
	echo '<td>'.$invoiceDate.'</td>
			</tr>
			</table>
			<br>';

	echo '<table class="products">
			<thead id="head">
			<tr>
			<th width=11%>Product Code:</th>
			<th width=44%>Product Description:</th>
			<th width=5%>UN</th>
			<th width=10%>Quantity</th>
			<th width=10%>Unit Price</th>
			<th width=5%>Tax</th>
			<th width=15%>Total Price</th>
			</tr>
			</thead>';

	
	
	for($i=0;$i<count($invoiceLines);$i++){
		$line=$invoiceLines[$i];

		$productQueryParams=array(
				array("ProductCode",array($line->ProductCode),"equal")
		);

		$products=Product::getInstancesByFields($db, $productQueryParams);
		$product=$products[0];


		echo'<tr>';
		echo '<td class="Number">'.$line->ProductCode.'</td>';
		echo '<td>'.$product->ProductDescription.'</td>';
		echo '<td class="Unit">'.$product->UnitOfMeasure.'</td>';
		echo '<td class="Number">'.$line->Quantity.'</td>';
		echo '<td class="Number">'.($line->UnitPrice/100).' &euro; </td>';
		echo '<td class="Unit">'.$line->Tax->TaxPercentage.'</td>';
		echo '<td class="Number">'.((int)($line->CreditAmount*($line->Tax->TaxPercentage/100+1))/100).' &euro;</td>
				</tr>';

	}
	
	for($i=0;$i<count($invoiceLines);$i++){
		$line=$invoiceLines[$i];
	
		$productQueryParams=array(
				array("ProductCode",array($line->ProductCode),"equal")
		);
	
		$products=Product::getInstancesByFields($db, $productQueryParams);
		$product=$products[0];
	
	
		echo'<tr>';
		echo '<td class="Number">'.$line->ProductCode.'</td>';
		echo '<td>'.$product->ProductDescription.'</td>';
		echo '<td class="Unit">'.$product->UnitOfMeasure.'</td>';
		echo '<td class="Number">'.$line->Quantity.'</td>';
		echo '<td class="Number">'.($line->UnitPrice/100).' &euro; </td>';
		echo '<td class="Unit">'.$line->Tax->TaxPercentage.'</td>';
		echo '<td class="Number">'.((int)($line->CreditAmount*($line->Tax->TaxPercentage/100+1))/100).' &euro;</td>
				</tr>';
	
	}
	
	?>
			
	</table>


</body>
</html>
