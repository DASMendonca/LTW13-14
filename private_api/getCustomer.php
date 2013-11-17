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
	
	$params=$_GET["params"];
	
	
	
	$customers=Product::getInstancesByFields($db, $params);
} catch (GeneralException $e) {
	echo '</fieldset>';
	die();
}

if($customers != NULL){
	?>
	<table id="search_results_tb">
		<tr>
		<th>Product Code</th><th>Product Description</th><th>Measure Unit</th><th>Price p/ Unit</th>
		</tr>
<?php
	foreach ($customers as $customer){
		echo utf8_encode('<tr>
				<td>' .$customer->CustomerID .'</td>
				<td>' .$customer->CustomerName .'</td>
				<td>' .$customer->CustomerTaxID .'</td>
				<td>' .$customer->email.' </td>
			<tr>');	
	}
	echo '</table>
		</fieldset>';	
}
?>