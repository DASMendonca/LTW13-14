<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="DetailsPrint.css" media="print">
<link rel="stylesheet" href="Details.css" media="screen">
<title>DetailCostumer</title>
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



	$customers=Customer::getInstancesByFields($db, $params);
	$customer=$customers[0];
	
	$CustomerCode=$customer->CustomerID;
	$CompanyName=$customer->CompanyName;
	$CustomerAddress=$customer->getAddress()->detail;
	$CustomerPostalCode1=$customer->getAddress()->postalCode1;
	$CustomerPostalCode2=$customer->getAddress()->postalCode2;
	$CustomerCity=$customer->getAddress()->city;
	$CustomerNif=$customer->CustomerTaxID;
	$CustomerEmail=$customer->email;
	
} catch (GeneralException $e) {

	$CustomerCode=-1;
	$CompanyName="No Name";
	$CustomerAddress="No Address";
	$CustomerPostalCode1="0000";
	$CustomerPostalCode2="000";
	$CustomerCity="No City";
	$CustomerNif="000000000";
	$CustomerEmail="noemail@nodomain.com";




	
}






echo '<p class="sheetID">Customer Data</p><br><br>';
echo '<p class="rowID">Customer Code: </p>';
echo '<p>'.$CustomerCode.'</p><br>';
echo '<p class="rowID">Name: </p>';
echo '<p>'.$CompanyName.'</p><br>';
echo '<p class="rowID">Address: </p>';
echo '<p>'.$CompanyName.'</p><br>';
echo '<p class="rowID">Zip Code: </p>';
echo '<p>'.$CustomerPostalCode1. '</p>';
echo '<p> - </p>';
echo '<p>'.$CustomerPostalCode2.' '.$CustomerCity.'</p><br>';
echo '<p class="rowID">NIF: </p>';
echo '<p>'.$CustomerNif.'</p><br>';
echo '<p class="rowID">e-mail: </p>';
echo '<p>'.$CustomerEmail.'</p>';

?>
<br>
<br>


</body>
</html>
