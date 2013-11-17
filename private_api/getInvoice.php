<?php

include '../classes.php';

header('Content-type: text/html; charset=UTF-8');
?>

<fieldset>
	<legend></legend><h2>Search Results</h2></legend>
	
<?php

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


try {
	if(!isset($_GET['params']))
		throw new GeneralException(new Err_MissingParameter("params"));
	
	
	
	
	$invoices=Invoice::getInstancesByFields($db, $_GET["params"]);
} catch (GeneralException $e) {
	echo '</fieldset>';
	die();
}

if($invoices != NULL){
	?>
	<table id="search_results_tb">
		<tr>
		<th>Invoice Number</th><th>Invoice Date</th><th>Costumer ID</th><th>Company Name</th><th>Gross Total</th>
		</tr>
<?php
	foreach ($invoices as $invoice){
		echo utf8_encode('<tr>
				<td>' .$invoice->InvoiceNo .'</td>
				<td>' .$invoice->InvoiceDate .'</td>
				<td>' .$invoice->CustomerID .'</td>
				<td>' .$invoice->CompanyName.'</td>
				<td>' .$invoice->GrossTotal.'</td>
			<tr>');	
	}
	echo '</table>
		</fieldset>';	
}
?>