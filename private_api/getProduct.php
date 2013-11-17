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
	$products=Product::getInstancesByFields($db, $_GET["params"]);
} catch (GeneralException $e) {
	echo '</fieldset>';
	die();
}

if($products != NULL){
	?>
	<table id="search_results_tb">
		<tr>
		<th>Product Code</th><th>Product Description</th><th>Measure Unit</th><th>Price p/ Unit</th>
		</tr>
<?php
	foreach ($products as $product){
		echo utf8_encode('<tr>
				<td>' .$product->ProductCode .'</td>
				<td>' .$product->ProductDescription .'</td>
				<td>' .$product->UnitOfMeasure .'</td>
				<td>' .($product->UnitPrice / 100).' &euro; </td>
			<tr>');	
	}
	echo '</table>
		</fieldset>';	
}
?>