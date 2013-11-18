<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="DetailsPrint.css" media="print">
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

	
	echo '<p class="sheetID">Customer Data</p>';
	echo '<br><br>';
	echo '<p class="rowID">Customer Code:</p>';
	echo '<p>'.$invoiceCustomerID.'</p>';
	echo '<br>';
	echo '<p class="rowID">Name:</p>';
	echo '<p>'.$invoiceCompanyName.'</p>';
	echo '<br>';
	echo '<p class="rowID">NIF:</p>';
	echo '<p>'.$invoiceNif.'</p>';
	echo '<br>';
	echo '<br>';

	echo '<p class="sheetID">Invoice</p>';
	echo '<br>';
	echo '<table class="invoice">';
	echo '<tr>';
	echo '<td class="rowID">Invoice Nr:</td>';
	echo '<td>'.$invoiceCode.'</td>';
	echo '<td class="rowID">Date:</td>';
	echo '<td>'.$invoiceDate.'</td>';
	echo '</tr>';
	echo '</table>';
	echo '<br>';

	echo '<p class="Articles">Products</p>
	<br>
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

	
	
	for($i=0;count($invoiceLines);$i++){
		$line=$invoiceLines[$i];
		
		$productQueryParams=array(
		array("ProductCode",array($line->ProductCode),"equal")
		);
		
		
		echo'<tr>';
		echo '<td>'.$line->ProductCode.'</td>';
		echo '<td>'.Produto1.'</td>';
		echo '<td>'.un.'</td>';
		echo '<td>'.'</td>';
		echo '<td>'.'</td>';
		echo'<td>'.'</td>';
		echo '<td>'.'</td>
		</tr>';
		



	}
	
	
	?>
			
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
		<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
				<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
		<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
		<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
		<tr>
			<td>00001</td>
			<td>Produto 1</td>
			<td>un</td>
			<td>1.00</td>
			<td>100.00</td>
			<td>23</td>
			<td>123.00</td>
		</tr>
	</table>


</body>
</html>
