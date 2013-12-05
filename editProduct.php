<?php
include './classes.php';
session_start();
header('Content-type: text/html');

if(!isset($_SESSION['customer']) || $_SESSION['customer']->Permission<2) 
	header("Location: onlineInvoiceSystem.php");



$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$products= array();



	$params=array(
			array("ProductCode",array($_REQUEST["param"]),"equal")
	);
	
	$products= Product::getInstancesByFields($db, $params);
	if(count($products)!=1)throw new GeneralException(new Err_Not_Found("products"));
	
	$product= $products[0];
	
	echo '
		<div class="update_div" name="Customer">
				<br><br>
			<form action="updateProduct.php" method="post" class="update_form">
				<fieldset>
					<legend><h2>Edit Product Information</h2></legend>
						<div id="ProductDescription"
							<label class="to_ident" for="ProducDescription">
							<input type="text" name="ProductDescription" id="ProductDescription" 
							placeholder="'.$product->ProductDescription.'" value="'.$product->ProductDescription.'"><br>
						</div>
						
						<div id="ProductTypeID">
							<label class="to_ident" for="ProductTypeID">
							<input type="text" name="ProductTypeID" id="ProductTypeID" 
							placeholder="'.$product->ProductTypeID.'" value="'.$product->ProductTypeID.'"><br>
						</div>
									
			
						<div id="UnitOfMeasure">
							<label class="to_ident" for="UnitOfMeasure">
							<input type="text" name="UnitOfMeasure" id="UnitOfMeasure" 
							placeholder="'.$product->UnitOfMeasure.'" value="'.$product->UnitOfMeasure.'"><br>
						</div>
									
						<div id="UnitPrice">
							<label class="to_ident" for="UnitPrice">
							<input type="text" name="UnitPrice" id="UnitPrice" 
							placeholder="'.$product->UnitPrice.'" value="'.$product->UnitPrice.'"><br>
						</div>
						<input type="button" id="save_edit" value="save">
				</fieldset>
			</form>
		</div>';
?>
					
'