<?php 
include 'classes.php';
session_start();

$db = new PDO('sqlite:./database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$product_types = ProductType::getInstancesByFields($db, array());


?>


<form class="to_db_form" name="Product" action="signup.php" method="post">
<br><br>
<label for="ProductDescription">Product Description</label> 	
<input type="text" placeholder="Description" name="ProductDescription"><br>

<label for="UnitPrice">Unit Price</label>	
<input type="number" placeholder="unit price" name="UnitPrice"><br>

<label for="UnitOfMeasure">Measure unit:</label> 	
<input type="text" placeholder="Measure Unit" name="UnitOfMeasure"><br>

<label for="ProductTypeID">Product Type</label>
<select name="ProductTypeID">';
<?php 
	foreach ( $product_types as $p_type ) {
		$p_type_desc = $p_type->typeDescription;
		$p_type_id = $p_type->typeID;
		echo '<option value="'. $p_type_id .'" 
		label="' . $p_type_desc . '">' . $p_type_desc . '</option>';
		}
?>
</select><br>


<input type="button" class="to_db_btn" value="Insert">
</form>