<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="DetailsPrint_2.css" media="print">
<link rel="stylesheet" href="Details_2.css" media="screen">
<title>DetailInvoice</title>
</head>
<?php 

include './classes.php';
$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
header('Content-type: text/html; charset=UTF-8');

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
echo utf8_encode('<body>
		<table class="Logo">
		<tr>
		<th>Online Invoice System</th>
		</tr>
		<tr>
		<td>Linguagens e Tecnologias Web</td>
		</tr>
		</table>');


echo utf8_encode('<p class="sheetID">Customer Data</p>
		<br><br>
		<p class="rowID">Customer Code: </p>
		<p>'.$invoiceCustomerID.'</p>
		<br>
		<p class="rowID">Name: </p>
		<p>'.$invoiceCompanyName.'</p>
		<br>
		<p class="rowID">NIF: </p>
		<p>'.$invoiceNif.'</p>
		<br><br>');

echo utf8_encode('<p class="sheetID">Invoice</p>
		<br>
		<table>
		<tr>
		<td class="rowID">Invoice Nr: </td>
		<td>'.$invoiceCode.'</td>
		<td class="rowID">Date: </td>
		<td>'.$invoiceDate.'</td>
		</tr>
		</table>
		<br>');

echo utf8_encode('<table class="products">
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
		</thead>');

$subTotal=0;
$taxTotal=0;

for($i=0;$i<count($invoiceLines);$i++){
	$line=$invoiceLines[$i];

	$productQueryParams=array(
			array("ProductCode",array($line->ProductCode),"equal")
	);

	$products=Product::getInstancesByFields($db, $productQueryParams);
	$product=$products[0];
	$tempTotal= number_format(($line->CreditAmount)/100,2);
	$subTotal+=$tempTotal;
	$taxTotal+=$tempTotal*($line->Tax->TaxPercentage/100);

	echo utf8_encode('<tr>');
	echo utf8_encode('<td class="Number">'.$line->ProductCode.'</td>');
	echo utf8_encode('<td>'.$product->ProductDescription.'</td>');
	echo utf8_encode('<td class="Unit">'.$product->UnitOfMeasure.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->Quantity,2).'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->UnitPrice/100,2).' &euro; </td>');
	echo utf8_encode('<td class="Unit">'.$line->Tax->TaxPercentage.'</td>');
	echo utf8_encode('<td class="Number">'.$tempTotal.' &euro;</td>
			</tr>');

}

for($i=0;$i<count($invoiceLines);$i++){
	$line=$invoiceLines[$i];

	$productQueryParams=array(
			array("ProductCode",array($line->ProductCode),"equal")
	);

	$products=Product::getInstancesByFields($db, $productQueryParams);
	$product=$products[0];
	$tempTotal= number_format(($line->CreditAmount)/100,2);
	$subTotal+=$tempTotal;
	$taxTotal+=$tempTotal*($line->Tax->TaxPercentage/100);

	echo utf8_encode('<tr>');
	echo utf8_encode('<td class="Number">'.$line->ProductCode.'</td>');
	echo utf8_encode('<td>'.$product->ProductDescription.'</td>');
	echo utf8_encode('<td class="Unit">'.$product->UnitOfMeasure.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->Quantity,2).'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->UnitPrice/100,2).' &euro; </td>');
	echo utf8_encode('<td class="Unit">'.$line->Tax->TaxPercentage.'</td>');
	echo utf8_encode('<td class="Number">'.$tempTotal.' &euro;</td>
			</tr>');
}

for($i=0;$i<count($invoiceLines);$i++){
	$line=$invoiceLines[$i];

	$productQueryParams=array(
			array("ProductCode",array($line->ProductCode),"equal")
	);

	$products=Product::getInstancesByFields($db, $productQueryParams);
	$product=$products[0];
	$tempTotal= number_format(($line->CreditAmount)/100,2);
	$subTotal+=$tempTotal;
	$taxTotal+=$tempTotal*($line->Tax->TaxPercentage/100);

	echo utf8_encode('<tr>');
	echo utf8_encode('<td class="Number">'.$line->ProductCode.'</td>');
	echo utf8_encode('<td>'.$product->ProductDescription.'</td>');
	echo utf8_encode('<td class="Unit">'.$product->UnitOfMeasure.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->Quantity,2).'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->UnitPrice/100,2).' &euro; </td>');
	echo utf8_encode('<td class="Unit">'.$line->Tax->TaxPercentage.'</td>');
	echo utf8_encode('<td class="Number">'.$tempTotal.' &euro;</td>
			</tr>');
}
echo '</table>';

echo utf8_encode('<br>
		<table class="Result">
		<tr>
		<th>Subtotal</th>
		<td width=50% class="Number">'.$subTotal.' &euro;</td>
		</tr>
		<tr>
		<th>Tax Total</th>
		<td class="Number">'.number_format($taxTotal,2).' &euro;</td>
		</tr>
		<tr>
		<th>Total</th>
		<td class="Number">'.number_format(($subTotal+$taxTotal),2).' &euro;</td>
		</tr>
		</table>');
?>


</body>
</html>
