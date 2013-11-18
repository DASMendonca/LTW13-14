<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="DetailsPrint.css" media="print">
<link rel="stylesheet" href="Details.css" media="screen">
<title>DetailProduct</title>
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
<br>



<?php

include './classes.php';
$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);



try {
	if(!isset($_GET['params']))
		throw new GeneralException(new Err_MissingParameter("params"));

	$params=array(json_decode($_GET["params"]));



	$products=Product::getInstancesByFields($db, $params);
	$product=$products[0];
	
	$typeID=$product->ProductTypeID;
	
	$typeQueryParams=array(
		array("ProductTypeID",array($typeID),"equal")
	);
	
	$types=ProductType::getInstancesByFields($db,$typeQueryParams);
	$type=$types[0];
	
	$TaxQueryParams=array(
		array("TaxID",array($type->taxID),"equal")
	);
	
	$taxes=Tax::getInstancesByFields($db,$TaxQueryParams);
	$tax=$taxes[0];
	
	
	$ProductCode=$product->ProductCode;
	$ProductDescription=$product->ProductDescription;
	$UnitPrice=$product->UnitPrice;
	$UnitOfMeasure=$product->UnitOfMeasure;
	$Tax=$tax->TaxPercentage;
	$ProductType=$type->typeDescription;
	$FinalPrice=($Tax/100+1)*$UnitPrice;
	
} catch (GeneralException $e) {

	$ProductCode=-1;
	$ProductDescription="No Description";
	$UnitPrice="0";
	$UnitOfMeasure="No Unit";
	$Tax=0;
	$FinalPrice=0;




	
}




echo '<p class="sheetID">Product Data</p><br><br>';
echo '<p class="rowID">Product Code: </p>';
echo '<p>'.$ProductCode.'</p><br>';
echo '<p class="rowID">Product Description: </p>';
echo '<p>'.$ProductDescription.'</p><br>';
echo '<p class="rowID">UN: </p>';
echo '<p>'.$UnitOfMeasure.'</p><br>';
echo '<p class="rowID">Unitary Price: </p>';
echo '<p>'.$UnitPrice.'</p><br>';
echo '<p class="rowID">Tax: </p>';
echo '<p>'.$Tax.' &#37; </p><br>';
echo '<p class="rowID">Selling Price: </p>';
echo '<p>'.$FinalPrice.'</p><br>';
echo '<p class="rowID">Product Type: </p>';
echo '<p>'.$type->typeDescription.'</p>';
?>
<br>
<br>
</body>
</html>
