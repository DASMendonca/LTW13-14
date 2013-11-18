<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="DetailsPrint.css" media="print">
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



<php

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
	
	$typeID=$product->$ProductTypeID;
	
	$typeQueryParams=array(
		array("ProductTypeID",array($typeID),"equal")
	);
	
	$type=ProductType::getInstancesByFields($db,$typeQueryParams);
	
	$TaxQueryParams=array(
		array("TaxID",array($type->taxID),"equal")
	);
	
	$tax=Tax::getInstancesByFields($db,$typeQueryParams);
	
	
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


?>

<p class="sheetID">Product Data</p><br><br>
<p class="rowID">Product Code: </p>
<p>0001 </p><br>
<p class="rowID">Product Description: </p>
<p>Cimento Normal Saco 35kg</p><br>
<p class="rowID">UN: </p>
<p>un </p><br>
<p class="rowID">Unitary Price: </p>
<p>3.17 </p><br>
<p class="rowID">Tax: </p>
<p>23%</p><br>
<p class="rowID">Selling Price: </p>
<p>3.19*1.23</p><br>
<p class="rowID">Product Type: </p>
<p>Material de Construção</p><br>
<br>
</body>
</html>
