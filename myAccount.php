<?php
include 'classes.php';
session_start();
?>


<?php

if(isset($_SESSION['customer'])){
	
echo '
<div class="update_div" name="Customer" id="true">
		<br><br>
		<form action="updateMyuser.php" method="post" class="update_form" name="CustomerID" id="'.$_SESSION['customer']->CustomerID.'">
			<fieldset>
				<legend><h2>Edit Information</h2></legend>
				<div id="CompanyName">
				<label class="to_ident" for="">Name</label>
				<input type="text" name="CompanyName" id="CompanyName" placeholder="'.$_SESSION['customer']->CompanyName.'" 
						value="'.$_SESSION['customer']->CompanyName.'">
				</div><br>

				<div id="Email">
				<label class="to_ident" for="email">E-mail</label>
				<input type="text" name="email" id="email" placeholder="'.$_SESSION['customer']->Email.'" 
						value="'.$_SESSION['customer']->Email.'">
				</div><br>
						
				<div id="CustomerTaxID">
				<label class="to_ident" for="CustomerTaxID">Tax ID</label>
				<input type="number" name="CustomerTaxID" id="CustomerTaxID" placeholder="'.$_SESSION['customer']->CustomerTaxID.'" 
						value="'.$_SESSION['customer']->CustomerTaxID.'"><
				</div><br>	

				<div id="Password">
				<label class="to_ident" for="password">Password</label>
				<input type="text" name="password" id="password" placeholder="'.$_SESSION['customer']->Password.'" 
						value="'.$_SESSION['customer']->Password.'">
				</div><br>
						
				<div id="Country">
				<label class="to_ident" for="Country">Country</label>
				<input type="text" name="Country" id="Country" placeholder="'.$_SESSION['customer']->getAddress()->Country.'" 
						value="'.$_SESSION['customer']->getAddress()->Country.'">
				</div><br>
				
				<div id="City">
				<label class="to_ident" for="City">City</label>
				<input type="text" name="City" id="City" placeholder="'.$_SESSION['customer']->getAddress()->City.'" 
						value="'.$_SESSION['customer']->getAddress()->City.'">
				</div><br>
						
				<div id="AddressDetail">
				<label class="to_ident" for="AddressDetail">Address Detail</label>
				<input type="text" name="AddressDetail" id="AddressDetail" placeholder="'.$_SESSION['customer']->getAddress()->AddressDetail.'" 
						value="'.$_SESSION['customer']->getAddress()->AddressDetail.'">
				</div><br>
					
				<div id="PostalCode1">
				<label class="to_ident" for="PostalCode1">Postal Code 1</label>
				<input type="text" name="PostalCode1" id="PostalCode1" placeholder="'.$_SESSION['customer']->getAddress()->PostalCode1.'" 
						value="'.$_SESSION['customer']->getAddress()->PostalCode1.'">
				</div><br>
				<div id ="PostalCode2">
				<label class="to_ident" for="PostalCode1">Postal Code 1</label>
				<input type="text" name="PostalCode2" id="PostalCode2" placeholder="'.$_SESSION['customer']->getAddress()->PostalCode2.'" 
						value="'.$_SESSION['customer']->getAddress()->PostalCode2.'">
				</div><br>
				<input type="button" id="save_edit" value="Save">				
			</fieldset>
		</form>
</div>';	
}
?>