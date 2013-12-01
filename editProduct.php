<?php
include '../classes.php';
header('Content-type: application/json');
session_start();

if(!isset($_SESSION['customer']) || $_SESSION['customer']->permission<2) 
	header("Location: onlineInvoiceSystem.php");



$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$products= array();

try {
	if(!isset($_REQUEST["ProductCode"])) throw new GeneralException(new Err_MissingParameter("ProductCode"));

	$params=array(
			array("CustomerID",array($_Request["CustomerID"]),"equal")
	);
	
	$products= Product::getInstancesByFields($db, $params);
	if(count($products)!=1)throw new GeneralException(new Err_Not_Found("products"));
	
	$product= $products[0];
	
	echo '
		<div id="product_ediction" name="'.$product->ProductCode.'">
				<br><br>
			<form action="updateProduct.php" method="post" class="update_form">
				<fieldset>
					<legend><h2>Edit Product Information</h2></legend>
						<label class="to_ident" for="ProducDescription">
						<input type="text" name="ProductDescription" id="ProductDescription" placeholder="'.$product->ProductDescription.'"><br>
						
						<label class="to_ident" for="ProductTypeID">
						<input type="text" name="ProductTypeID" id="ProductTypeID" placeholder="'.$product->ProductTypeID.'"><br>

						<label class="to_ident" for="UnitOfMeasure">
						<input type="text" name="UnitOfMeasure" id="UnitOfMeasure" placeholder="'.$product->UnitOfMeasure.'"><br>
								
						<label class="to_ident" for="UnitPrice">
						<input type="text" name="UnitPrice" id="UnitPrice" placeholder="'.$product->UnitPrice.'"><br>
								
						<input type="button" value="save">
				</fieldset>
			</form>
		</div>';
	} catch (GeneralException  $e) {
		echo json_encode($e);
	}
	
?>
					
'