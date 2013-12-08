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
	$invoiceStartDate=$invoice->StartDate;
	$invoiceGenerationDate= $invoice->GenerationDate;
	$invoiceEndDate=$invoice->EndDate;
	$invoiceCompanyName=$invoice->Customer->CompanyName;
	$invoiceGrossTotal=$invoice->GrossTotal;
	$invoiceCustomerID=$invoice->getCustomerId();
	$invoiceCutomerAddress=$invoice->Customer->BillingAddress->AddressDetail;
	$invoiceCutomerCP1=$invoice->Customer->BillingAddress->PostalCode1;
	$invoiceCutomerCP2=$invoice->Customer->BillingAddress->PostalCode2;
	$invoiceCutomerCity=$invoice->Customer->BillingAddress->City;

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
		<th>ACompany</th>
		</tr>
		<tr>
		<td>Rua Dr. Roberto Frias,127</td>
		</tr>
		<tr>
		<td>4200-465 Porto</td>
		</tr>
		<tr>
		<td>Telefone: +351 22 508 14</td>
		</tr>
		<tr>
		<td>Fax: +351 22 508 14 40</td>
		</tr>
		<tr>
		<td>NIF: 240 022 570</td>
		</tr>
		</table>');


echo utf8_encode('<table class="Customer">
		<tr>
		<td>Exmo.(s) Senhor(es)</td>
		</tr>
		<tr>
		<td>'.$invoiceCompanyName.'</td>
		</tr>
		<tr>
		<td>'.$invoiceCutomerAddress.'</td>
		</tr>
		<tr>
		<td>'.$invoiceCutomerCP1.' - '.$invoiceCutomerCP2.' '.$invoiceCutomerCity.'</td>
		</tr>
		</table>');


echo utf8_encode('<table class="InvoiceDetails">
		<tr>
		<th width=16%>Customer Number:</th>
		<td width=10% class="Number">'.$invoiceCustomerID.'</td>
		<td width=34% id="Empty"></td>
		<th width=10%>Invoice Nr: </th>
		<td width=10% class="Number">'.$invoiceCode.'</td>
		<td width=7%>Date: </td>
		<td width=13% class="Unit">'.$invoiceEndDate.'</td>
		</tr>
		</table>');

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
	$tempTotal= ($line->CreditAmount)/100;
	$subTotal+=$tempTotal;
	$taxTotal+=$tempTotal*($line->Tax->TaxPercentage/100);

	echo utf8_encode('<tr>');
	echo utf8_encode('<td class="Number">'.$line->ProductCode.'</td>');
	echo utf8_encode('<td>'.$product->ProductDescription.'</td>');
	echo utf8_encode('<td class="Unit">'.$product->UnitOfMeasure.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->Quantity,2,","," ").'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->UnitPrice/100,2,","," ").' &euro; </td>');
	echo utf8_encode('<td class="Unit">'.$line->Tax->TaxPercentage.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($tempTotal,2,","," ").' &euro;</td>
			</tr>');

}

for($i=0;$i<count($invoiceLines);$i++){
	$line=$invoiceLines[$i];

	$productQueryParams=array(
			array("ProductCode",array($line->ProductCode),"equal")
	);

	$products=Product::getInstancesByFields($db, $productQueryParams);
	$product=$products[0];
	$tempTotal= ($line->CreditAmount)/100;
	$subTotal+=$tempTotal;
	$taxTotal+=$tempTotal*($line->Tax->TaxPercentage/100);

	echo utf8_encode('<tr>');
	echo utf8_encode('<td class="Number">'.$line->ProductCode.'</td>');
	echo utf8_encode('<td>'.$product->ProductDescription.'</td>');
	echo utf8_encode('<td class="Unit">'.$product->UnitOfMeasure.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->Quantity,2,","," ").'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->UnitPrice/100,2,","," ").' &euro; </td>');
	echo utf8_encode('<td class="Unit">'.$line->Tax->TaxPercentage.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($tempTotal,2,","," ").' &euro;</td>
			</tr>');
}

for($i=0;$i<count($invoiceLines);$i++){
	$line=$invoiceLines[$i];

	$productQueryParams=array(
			array("ProductCode",array($line->ProductCode),"equal")
	);

	$products=Product::getInstancesByFields($db, $productQueryParams);
	$product=$products[0];
	$tempTotal= ($line->CreditAmount)/100;
	$subTotal+=$tempTotal;
	$taxTotal+=$tempTotal*($line->Tax->TaxPercentage/100);

	echo utf8_encode('<tr>');
	echo utf8_encode('<td class="Number">'.$line->ProductCode.'</td>');
	echo utf8_encode('<td>'.$product->ProductDescription.'</td>');
	echo utf8_encode('<td class="Unit">'.$product->UnitOfMeasure.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->Quantity,2,","," ").'</td>');
	echo utf8_encode('<td class="Number">'.number_format($line->UnitPrice/100,2,","," ").' &euro; </td>');
	echo utf8_encode('<td class="Unit">'.$line->Tax->TaxPercentage.'</td>');
	echo utf8_encode('<td class="Number">'.number_format($tempTotal,2,","," ").' &euro;</td>
			</tr>');
}
echo '</table>';

echo utf8_encode('<br>
		<table class="Result">
		<tr>
		<th>Subtotal</th>
		<td width=50% class="Number">'.number_format($subTotal,2,","," ").' &euro;</td>
		</tr>
		<tr>
		<th>Tax Total</th>
		<td class="Number">'.number_format($taxTotal,2,","," ").' &euro;</td>
		</tr>
		<tr>
		<th>Total</th>
		<td class="Number">'.number_format(($subTotal+$taxTotal),2,","," ").' &euro;</td>
		</tr>
		</table>');
?>


</body>
</html>
