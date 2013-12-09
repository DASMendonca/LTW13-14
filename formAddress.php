<?php
	session_start();

	
	//if($_SESSION['Customer']->permission>1){
		?>
		<form class="to_db_form" action="saveAddress.php" method="post">
			Country:<br>
			<input type="text" placeholder="Country" name="Country"><br>
			Address:<br>
			<input type="text" placeholder="Address" name="AddressDetail"><br>
			City:<br>
			<input type="text" placeholder="City" name="City"><br>
			Postal-Code:<br>
			<input type="number" placeholder="Postal-Code" name="PostalCode1">-<input type="number" placeholder="PostalCode2" name="PostalCode2"><br>
			<input type="submit" placeholder="Sign Up">
		</form>
	
	<?php 
	//}
// 	else{
// 		//header("Location: onlineInvoiceSystem.php");
// 		}
	?>