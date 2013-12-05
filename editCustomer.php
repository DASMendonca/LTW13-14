<?php
include './classes.php';
session_start();
header('Content-type: text/html');
?>

<?php 

if(!isset($_SESSION['customer']) || $_SESSION['customer']->Permission<2) 
	header("Location: onlineInvoiceSystem.php");
	

	$db = new PDO('sqlite:./database.sqlite');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	
	$params=array(
			array("CustomerID",array($_GET["CustomerID"]),"equal")
	
	);
	

	$customers = Customer::getInstancesByFields($db, $params);
	$customer= $customers[0];

	 
	 echo '
<div class="update_div" name="Customer">
		<br><br>
		<form action="updateMyuser.php" method="post" class="update_form" name="CustomerID" id="'.$customer->CustomerID.'">
			<fieldset>
				<legend><h2>Edit Information</h2></legend>
				<div id="CompanyName">
				<label class="to_ident" for="">Name</label>
				<input type="text" name="CompanyName" id="CompanyName" placeholder="'.$customer->CompanyName.'" value="'.$customer->CompanyName.'"><br>
				</div>

				<div id="Email">
				<label class="to_ident" for="email">E-mail</label>
				<input type="text" name="email" id="email" placeholder="'.$customer->Email.'" value="'.$customer->Email.'"><br>
				</div>
						
				<div id="CustomerTaxID">
				<label class="to_ident" for="CustomerTaxID">Tax ID</label>
				<input type="number" name="CustomerTaxID" id="CustomerTaxID" placeholder="'.$customer->CustomerTaxID.'" value="'.$customer->CustomerTaxID.'"><br>
				</div>		

				<div id="Password">
				<label class="to_ident" for="password">Password</label>
				<input type="text" name="password" id="password" placeholder="'.$customer->Password.'" value="'.$customer->Password.'"><br>
				</div>
						
				<div id="Country">
				<label class="to_ident" for="Country">Country</label>
				<input type="text" name="Country" id="Country" placeholder="'.$customer->getAddress()->Country.'" value="'.$customer->getAddress()->Country.'"><br>
				</div>
				
				<div id="City">
				<label class="to_ident" for="City"></label>
				<input type="text" name="City" id="City" placeholder="'.$customer->getAddress()->City.'" value="'.$customer->getAddress()->City.'"><br>
				</div>
						
				<div id="AddressDetail">
				<label class="to_ident" for="AddressDetail"></label>
				<input type="text" name="AddressDetail" id="AddressDetail" placeholder="'.$customer->getAddress()->AddressDetail.'" value="'.$customer->getAddress()->AddressDetail.'"><br>
				</div>
					
				<div id="PostalCode1">
				<label class="to_ident" for="PostalCode1"></label>
				<input type="text" name="PostalCode1" id="PostalCode1" placeholder="'.$customer->getAddress()->PostalCode1.'" value="'.$customer->getAddress()->PostalCode1.'">-
				</div>
				<div id ="PostalCode2">
				<input type="text" name="PostalCode2" id="PostalCode2" placeholder="'.$customer->getAddress()->PostalCode2.'" value="'.$customer->getAddress()->PostalCode2.'"><br>
				</div>
				<input type="button" id="save_edit" value="Save">				
			</fieldset>
		</form>
</div>';
	
?>